<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils;

use PHPModelGenerator\Exception\FileSystemException;
use PHPModelGenerator\Exception\RenderException;
use PHPModelGenerator\Exception\SchemaException;
use PHPModelGenerator\Format\FormatValidatorFromRegEx;
use PHPModelGenerator\Model\GeneratorConfiguration;
use PHPModelGenerator\ModelGenerator as PHPModelGenerator;
use SuppCore\CalculoBackend\Helper\Utils\JustFileProvider;
use SuppCore\CalculoBackend\Helper\Utils\Str;
use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

/**
 * Class ModelGenerator.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModelGenerator
{
    private string $schemaDir = "";
    private string $schemaUri = "";
    private string $modelNamespace = "";
    private string $definitionsDir = "";

    public function setDefinitionsDir(string $definitionsDir): self
    {
        $this->definitionsDir = $definitionsDir;
        return $this;
    }

    protected function getDefinitionsDir(): string
    {
        return $this->definitionsDir;
    }

    public function setSchemaUri(string $schemaUri): self
    {
        $this->schemaUri = $schemaUri;
        return $this;
    }

    protected function getSchemaUri(): string
    {
         return $this->schemaUri;
    }

    public function setSchemaDir(string $schemaDir): self
    {
        $this->schemaDir = $schemaDir;
        return $this;
    }

    protected function getSchemaDir(): string
    {
        return $this->schemaDir;
    }

    public function setModelNamespace(string $modelNamespace): self
    {
        $this->modelNamespace = $modelNamespace;
        return $this;
    }

    protected function getModelNamespace(): string
    {
        return $this->modelNamespace;
    }

    final protected function getModelDir(string $schemaName): string
    {
        return rtrim($this->getSchemaDir(), '/').'/Models/'.Str::classNameCase($schemaName);
    }

    final protected function getModelPrefix(string $schemaName): string
    {
        return rtrim($this->getModelNamespace(), '\\').'\\'.Str::classNameCase($schemaName).'\\';
    }

    final public function getJsonSchemaUrlVersion(): string
    {
        return 'http://json-schema.org/draft-07/schema#';
    }

    final public function getJsonSchemaContent(): string
    {
        return file_get_contents(rtrim(dirname(__DIR__).'/JsonSchema', '/').'/DRAFT07.schema.json');
    }

    /**
     * @throws Exception
     * @throws InvalidValue
     */
    public function validateSchema(string $jsonFileContent, ?string $schemaContent = null): void
    {
        $schemaContentObject = $this->toLocalDataSchema($jsonFileContent);
        if ($schemaContent) {
            // Validamos o json com o schema passado
            $schemaContent = $this->toLocalDataSchema($schemaContent);
            $schemaDraft = Schema::import(json_decode($schemaContent));
            $schemaDraft->in(json_decode($schemaContentObject));
        } else {
            // Validamos apenas se o json é válido como schema
            // TODO Ao validar schemas com $ref internas e externas está ocorrendo erro, por isso do skipValidation
            $schemaContext = new Context();
            $schemaContext->skipValidation = true;
            Schema::import(json_decode($schemaContentObject), $schemaContext);
        }
    }

    public function saveSchema(string $schemaName, string $schemaContent): void
    {
        $conteudo = $this->toCustomDataSchema($schemaContent);
        file_put_contents(
            rtrim($this->getSchemaDir(), '/')."/$schemaName.schema.json",
            $conteudo
        );
    }

    /**
     * @param string $schemaName
     * @param string $schemaContent
     * @param bool $outputEnable
     *
     * @throws Exception
     * @throws FileSystemException
     * @throws InvalidValue
     * @throws RenderException
     * @throws SchemaException
     */
    public function generate(
        string $schemaName,
        string $schemaContent,
        bool $outputEnable = false
    ): void {
        $this->validateSchema($schemaContent);
        $this->saveSchema($schemaName, $schemaContent);

        $configuration = (new GeneratorConfiguration())
            ->setNamespacePrefix($this->getModelPrefix($schemaName))
            ->setSerialization(true)
            ->setImmutable(false)
            ->setDefaultArraysToEmptyArray(true)
            ->setOutputEnabled($outputEnable)
            ->addFormat(
                'date-time',
                new FormatValidatorFromRegEx(
                    "/^([0-9]+)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])[Tt]([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]|60)(\.[0-9]+)?(([Zz])|([\+|\-]([01][0-9]|2[0-3]):[0-5][0-9]))$/"
                )
            );

        (new PHPModelGenerator($configuration))
            ->generateModelDirectory($this->getModelDir($schemaName))
            ->generateModels(
                new JustFileProvider(
                    rtrim($this->getSchemaDir(), '/')."/$schemaName.schema.json"
                ),
                $this->getModelDir($schemaName)
            );
    }

    /** @noinspection PhpUnused */
    public function fromUriToDataSchema(string $uriDataSchema): string
    {
        return str_replace($this->getSchemaUri(), '{{uri}}', $uriDataSchema);
    }

    /** @noinspection PhpUnused */
    public function fromLocalToDataSchema(string $localDataSchema): string
    {
        return str_replace($this->getSchemaDir(), '{{uri}}', $localDataSchema);
    }

    /** @noinspection PhpUnused */
    public function fromCustomToDataSchema(string $customDataSchema): string
    {
        return preg_replace(
            "/\"(\w+)\.schema\.json(.+)\"/",
            '"{{uri}}$1.schema.json$2"',
            $customDataSchema
        );
    }

    /** @noinspection PhpUnused */
    public function toUriDataSchema(string $dataSchema): string
    {
        /* @noinspection RegExpRedundantEscape */
        return preg_replace(
            "/\{\{uri\}\}/",
            "{$this->getSchemaUri()}",
            $dataSchema
        );
    }

    // https://stackoverflow.com/questions/2637945/getting-relative-path-from-absolute-path-in-php
    private function get_rel_path(string $from, string $to): string
    {
        /* Find position of first difference between the two paths */
        $matchlen = strspn($from ^ $to, "\0");
        /* Search backwards for the next '/' */
        $lastslash = strrpos($from, '/', $matchlen - strlen($from) - 1) + 1;
        /* Count the slashes in $from after that position */
        $countslashes = substr_count($from, '/', $lastslash) + 1;

        return $countslashes ? str_repeat('../', $countslashes).substr($to, $lastslash) : '.';
    }

    function getRelativePath(string $from, string $to): string
    {
        $dir = explode(DIRECTORY_SEPARATOR, is_file($from) ? dirname($from) : rtrim($from, DIRECTORY_SEPARATOR));
        $file = explode(DIRECTORY_SEPARATOR, $to);

        while ($dir && $file && ($dir[0] == $file[0])) {
            array_shift($dir);
            array_shift($file);
        }
        return str_repeat('..'.DIRECTORY_SEPARATOR, count($dir)) . implode(DIRECTORY_SEPARATOR, $file);
    }

    public function toDefinitionsDataSchema(string $dataSchema, bool $rel_pat = false): string
    {
        $path = $rel_pat ?
            $this->getRelativePath($this->getSchemaDir(), $this->getDefinitionsDir()) :
            $this->getDefinitionsDir();
        /* @noinspection RegExpRedundantEscape */
        return preg_replace(
            "/\{\{uri\}\}/",
            "{$path}/",
            $dataSchema
        );
    }

    public function toLocalDataSchema(string $dataSchema): string
    {
        /* @noinspection RegExpRedundantEscape */
        return preg_replace(
            "/\{\{uri\}\}/",
            "{$this->getSchemaDir()}/",
            $this->toDefinitionsDataSchema($dataSchema)
        );
    }

    public function toCustomDataSchema(string $dataSchema): string
    {
        /* @noinspection RegExpRedundantEscape */
        return preg_replace(
            "/\{\{uri\}\}/",
            '',
            $this->toDefinitionsDataSchema($dataSchema, true)
        );
    }
}
