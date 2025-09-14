<?php

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\JMSSetList;
use Rector\Symfony\Set\SensiolabsSetList;
use Rector\Symfony\Set\SymfonySetList;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable;
use SuppCore\AdministrativoBackend\Form\Annotations as Form;
use SuppCore\AdministrativoBackend\Mapper\Annotations as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;

return static function (RectorConfig $rectorConfig): void {
    // is there a file you need to skip?
    $rectorConfig->skip([
        __DIR__.'/src/DataFixtures',
    ]);

    $rectorConfig->symfonyContainerXml(__DIR__.'/var/cache/dev/SuppCore_AdministrativoBackend_KernelDevDebugContainer.xml');

    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        // form
        new AnnotationToAttribute(Form\Form::class),
        new AnnotationToAttribute(Form\Field::class),
        new AnnotationToAttribute(Form\Method::class),
        new AnnotationToAttribute(Form\Cacheable::class),
        // mapper
        new AnnotationToAttribute(DTOMapper\JsonLD::class),
        new AnnotationToAttribute(DTOMapper\Mapper::class),
        new AnnotationToAttribute(DTOMapper\Property::class),
        // doctrine
        new AnnotationToAttribute(Enableable\Enableable::class),
        new AnnotationToAttribute(Immutable\Immutable::class),
        // assert
        new AnnotationToAttribute(AppAssert\DtoUniqueEntity::class),
        new AnnotationToAttribute(AppAssert\CpfCnpj::class),
        // open api
        new AnnotationToAttribute(RestApiDoc::class),
        new AnnotationToAttribute(OA\AdditionalProperties::class),
        new AnnotationToAttribute(OA\Attachable::class),
        new AnnotationToAttribute(OA\Components::class),
        new AnnotationToAttribute(OA\Contact::class),
        new AnnotationToAttribute(OA\Delete::class),
        new AnnotationToAttribute(OA\Discriminator::class),
        new AnnotationToAttribute(OA\Examples::class),
        new AnnotationToAttribute(OA\ExternalDocumentation::class),
        new AnnotationToAttribute(OA\Flow::class),
        new AnnotationToAttribute(OA\Get::class),
        new AnnotationToAttribute(OA\Head::class),
        new AnnotationToAttribute(OA\Header::class),
        new AnnotationToAttribute(OA\Info::class),
        new AnnotationToAttribute(OA\Items::class),
        new AnnotationToAttribute(OA\JsonContent::class),
        new AnnotationToAttribute(OA\License::class),
        new AnnotationToAttribute(OA\Link::class),
        new AnnotationToAttribute(OA\MediaType::class),
        new AnnotationToAttribute(OA\OpenApi::class),
        new AnnotationToAttribute(OA\Operation::class),
        new AnnotationToAttribute(OA\Options::class),
        new AnnotationToAttribute(OA\Parameter::class),
        new AnnotationToAttribute(OA\Patch::class),
        new AnnotationToAttribute(OA\PathItem::class),
        new AnnotationToAttribute(OA\PathParameter::class),
        new AnnotationToAttribute(OA\Post::class),
        new AnnotationToAttribute(OA\Property::class),
        new AnnotationToAttribute(OA\Put::class),
        new AnnotationToAttribute(OA\RequestBody::class),
        new AnnotationToAttribute(OA\Response::class),
        new AnnotationToAttribute(OA\Schema::class),
        new AnnotationToAttribute(OA\SecurityScheme::class),
        new AnnotationToAttribute(OA\Server::class),
        new AnnotationToAttribute(OA\ServerVariable::class),
        new AnnotationToAttribute(OA\Tag::class),
        new AnnotationToAttribute(OA\Trace::class),
        new AnnotationToAttribute(OA\Xml::class),
        new AnnotationToAttribute(OA\XmlContent::class),
        // dms
        new AnnotationToAttribute(Filter\Alnum::class),
        new AnnotationToAttribute(Filter\Alpha::class),
        new AnnotationToAttribute(Filter\Boolean::class),
        new AnnotationToAttribute(Filter\Callback::class),
        new AnnotationToAttribute(Filter\Digits::class),
        new AnnotationToAttribute(Filter\Float::class),
        new AnnotationToAttribute(Filter\HtmlEntities::class),
        new AnnotationToAttribute(Filter\Int::class),
        new AnnotationToAttribute(Filter\PregReplace::class),
        new AnnotationToAttribute(Filter\StripNewlines::class),
        new AnnotationToAttribute(Filter\StripTags::class),
        new AnnotationToAttribute(Filter\ToLower::class),
        new AnnotationToAttribute(Filter\ToUpper::class),
        new AnnotationToAttribute(Filter\Trim::class),
        new AnnotationToAttribute(Filter\Zend::class),
        // nelmio
        new AnnotationToAttribute(Security::class),
    ]);

    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        // SetList::TYPE_DECLARATION_STRICT,
        SetList::PHP_81,
        SymfonySetList::SYMFONY_61,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        DoctrineSetList::DOCTRINE_DBAL_30,
        DoctrineSetList::DOCTRINE_ORM_213,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::GEDMO_ANNOTATIONS_TO_ATTRIBUTES,
        SensiolabsSetList::FRAMEWORK_EXTRA_61,
        JMSSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
};
