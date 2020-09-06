<?php

namespace Botble\Paymentmanagement\Listeners;

use Botble\Paymentmanagement\Repositories\Interfaces\CategoryInterface;
use Botble\Paymentmanagement\Repositories\Interfaces\PaymentManagementInterface;
use Botble\Paymentmanagement\Repositories\Interfaces\TagInterface;
use SiteMapManager;

class RenderingSiteMapListener
{
    /**
     * @var PostInterface
     */
    protected $paymentmanagementRepository;

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
     * @param PostInterface $postRepository
     * @param CategoryInterface $categoryRepository
     * @param TagInterface $tagRepository
     */
    public function __construct(
        PaymentManagementInterface $paymentmanagementRepository,
        CategoryInterface $categoryRepository,
        TagInterface $tagRepository
    ) {
        $this->paymentmanagementRepository = $paymentmanagementRepository;
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
        $posts = $this->paymentmanagementRepository->getDataSiteMap();

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
