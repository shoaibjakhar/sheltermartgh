<?php

namespace Botble\Blog\Listeners;

use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\SettingInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use SiteMapManager;

class RenderingSiteMapListener
{
    /**
     * @var SettingInterface
     */
    protected $postRepository;

    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * @var TagInterface
     */
    protected $tagRepository;

    /**
     * RenderingSiteMapListener constructor.
     * @param SettingInterface $postRepository
     * @param CategoryInterface $categoryRepository
     * @param TagInterface $tagRepository
     */
    public function __construct(
        SettingInterface $postRepository,
        CategoryInterface $categoryRepository,
        TagInterface $tagRepository
    ) {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        $posts = $this->postRepository->getDataSiteMap();

        foreach ($posts as $post) {
            SiteMapManager::add($post->url, $post->updated_at, '0.8', 'daily');
        }

        $categories = $this->categoryRepository->getDataSiteMap();

        foreach ($categories as $category) {
            SiteMapManager::add($category->url, $category->updated_at, '0.8', 'daily');
        }

        $tags = $this->tagRepository->getDataSiteMap();

        foreach ($tags as $tag) {
            SiteMapManager::add($tag->url, $tag->updated_at, '0.3', 'weekly');
        }
    }
}
