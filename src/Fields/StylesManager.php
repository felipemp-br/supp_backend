<?php

declare(strict_types=1);
/**
 * /src/Fields/StylesManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields;

use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class StylesManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class StylesManager
{
    /**
     * @var StyleInterface[]
     */
    protected array $styles = [];

    /**
     * @return StyleInterface[]
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * @param StyleInterface[] $styles
     */
    public function setStyles(array $styles): void
    {
        $this->styles = $styles;
    }

    public function addStyle(StyleInterface $style): void
    {
        $this->styles[$style->getOrder()][] = $style;
    }

    public function select(EntityInterface|ComponenteDigital $componenteDigital): array
    {
        ksort($this->styles);

        foreach ($this->styles as $stylesOrdered) {
            foreach ($stylesOrdered as $style) {
                $css = $style->support($componenteDigital);
                if ($css) {
                    return $css;
                }
            }
        }
    }
}
