<?php

namespace EMS\CoreBundle\Service;

use EMS\CommonBundle\Entity\EntityInterface;
use EMS\CoreBundle\Entity\WysiwygStylesSet;
use EMS\CoreBundle\Repository\WysiwygStylesSetRepository;
use Psr\Log\LoggerInterface;

class WysiwygStylesSetService implements EntityServiceInterface
{
    public function __construct(private readonly WysiwygStylesSetRepository $wysiwygStylesSetRepository, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @return WysiwygStylesSet[]
     */
    public function getStylesSets(): array
    {
        static $stylesSets = null;
        if (null !== $stylesSets) {
            return $stylesSets;
        }

        return $this->wysiwygStylesSetRepository->findAll();
    }

    public function getByName(?string $name): ?WysiwygStylesSet
    {
        if (null === $name) {
            foreach ($this->getStylesSets() as $stylesSet) {
                return $stylesSet;
            }

            return null;
        }

        return $this->wysiwygStylesSetRepository->getByName($name);
    }

    public function getById(int $id): ?WysiwygStylesSet
    {
        return $this->wysiwygStylesSetRepository->findById($id);
    }

    /**
     * @param string[] $ids
     */
    public function reorderByIds(array $ids): void
    {
        $counter = 1;
        foreach ($ids as $id) {
            $wysiwyg_styles_set = $this->wysiwygStylesSetRepository->getById($id);
            $wysiwyg_styles_set->setOrderKey($counter++);
            $this->wysiwygStylesSetRepository->create($wysiwyg_styles_set);
        }
    }

    /**
     * @param string[] $ids
     */
    public function deleteByIds(array $ids): void
    {
        foreach ($this->wysiwygStylesSetRepository->getByIds($ids) as $wysiwygStylesSet) {
            $this->delete($wysiwygStylesSet);
        }
    }

    public function delete(WysiwygStylesSet $wysiwygStylesSet): void
    {
        $name = $wysiwygStylesSet->getName();
        $this->wysiwygStylesSetRepository->delete($wysiwygStylesSet);
        $this->logger->warning('log.service.wysiwyg_styles_set.delete', [
            'name' => $name,
        ]);
    }

    public function save(WysiwygStylesSet $stylesSet): void
    {
        $this->wysiwygStylesSetRepository->update($stylesSet);
        $this->logger->notice('service.wysiwyg_styles_set.updated', [
            'wysiwyg_styles_set_name' => $stylesSet->getName(),
        ]);
    }

    public function remove(WysiwygStylesSet $stylesSet): void
    {
        $name = $stylesSet->getName();
        $this->wysiwygStylesSetRepository->delete($stylesSet);
        $this->logger->notice('service.wysiwyg_styles_set.deleted', [
            'wysiwyg_styles_set_name' => $name,
        ]);
    }

    public function isSortable(): bool
    {
        return true;
    }

    public function get(int $from, int $size, ?string $orderField, string $orderDirection, string $searchValue, $context = null): array
    {
        if (null !== $context) {
            throw new \RuntimeException('Unexpected not null context');
        }

        return $this->wysiwygStylesSetRepository->get($from, $size, $orderField, $orderDirection, $searchValue);
    }

    public function getEntityName(): string
    {
        return 'wysiwyg-style-set';
    }

    /**
     * @return string[]
     */
    public function getAliasesName(): array
    {
        return [
            'wysiwyg-style-sets',
            'WysiwygStyleSet',
            'WysiwygStyleSets',
        ];
    }

    public function count(string $searchValue = '', $context = null): int
    {
        if (null !== $context) {
            throw new \RuntimeException('Unexpected not null context');
        }

        return $this->wysiwygStylesSetRepository->counter($searchValue);
    }

    public function getByItemName(string $name): ?EntityInterface
    {
        return $this->wysiwygStylesSetRepository->getByName($name);
    }

    public function updateEntityFromJson(EntityInterface $entity, string $json): EntityInterface
    {
        if (!$entity instanceof WysiwygStylesSet) {
            throw new \RuntimeException('Unexpected object class');
        }
        $stylesSet = WysiwygStylesSet::fromJson($json, $entity);
        $this->wysiwygStylesSetRepository->update($stylesSet);

        return $stylesSet;
    }

    public function createEntityFromJson(string $json, ?string $name = null): EntityInterface
    {
        $styleSet = WysiwygStylesSet::fromJson($json);
        if (null !== $name && $styleSet->getName() !== $name) {
            throw new \RuntimeException(\sprintf('WYSIWYG StylesSet name mismatched: %s vs %s', $styleSet->getName(), $name));
        }
        $this->wysiwygStylesSetRepository->update($styleSet);

        return $styleSet;
    }

    public function deleteByItemName(string $name): string
    {
        $styleSet = $this->wysiwygStylesSetRepository->getByName($name);
        if (null === $styleSet) {
            throw new \RuntimeException(\sprintf('WWYSIWYG StylesSet %s not found', $name));
        }
        $id = $styleSet->getId();
        $this->wysiwygStylesSetRepository->delete($styleSet);

        return \strval($id);
    }
}
