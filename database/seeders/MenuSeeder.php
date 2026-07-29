<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Language\Models\LanguageMeta;
use Botble\Menu\Facades\Menu;
use Botble\Menu\Models\Menu as MenuModel;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MenuSeeder extends BaseSeeder
{
    public function run(): void
    {
        MenuModel::query()->truncate();
        MenuLocation::query()->truncate();
        MenuNode::query()->truncate();

        $this->createMainMenu();
        $this->createQuickLinksMenu();

        Menu::clearCacheMenuItems();
    }

    protected function createMainMenu(): void
    {
        $enData = [
            'name' => 'Main menu',
            'slug' => 'main-menu',
            'location' => 'main-menu',
            'items' => [
                [
                    'title' => 'Home',
                    'url' => '/',
                    'icon_font' => 'elegant-icon icon_house_alt mr-5',
                    'children' => [
                        ['title' => 'Home default', 'url' => '/'],
                        ['title' => 'Home 2', 'reference_id' => 2, 'reference_type' => Page::class],
                        ['title' => 'Home 3', 'reference_id' => 3, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Travel', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Destination', 'reference_id' => 4, 'reference_type' => Category::class],
                ['title' => 'Hotels', 'reference_id' => 6, 'reference_type' => Category::class],
                ['title' => 'Lifestyle', 'reference_id' => 9, 'reference_type' => Category::class],
                [
                    'title' => 'Blog',
                    'reference_id' => 4,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Grid layout', 'reference_id' => 9, 'reference_type' => Page::class],
                        ['title' => 'List layout', 'reference_id' => 7, 'reference_type' => Page::class],
                        ['title' => 'Big layout', 'reference_id' => 8, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Galleries', 'url' => '/galleries'],
                ['title' => 'Contact', 'reference_id' => 5, 'reference_type' => Page::class],
            ],
        ];

        $arData = [
            'name' => 'القائمة الرئيسية',
            'slug' => 'main-menu-ar',
            'location' => 'main-menu',
            'items' => [
                [
                    'title' => 'الرئيسية',
                    'url' => '/',
                    'icon_font' => 'elegant-icon icon_house_alt mr-5',
                    'children' => [
                        ['title' => 'الرئيسية الافتراضية', 'url' => '/'],
                        ['title' => 'الرئيسية 2', 'reference_id' => 2, 'reference_type' => Page::class],
                        ['title' => 'الرئيسية 3', 'reference_id' => 3, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'سفر', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'وجهات', 'reference_id' => 4, 'reference_type' => Category::class],
                ['title' => 'فنادق', 'reference_id' => 6, 'reference_type' => Category::class],
                ['title' => 'نمط الحياة', 'reference_id' => 9, 'reference_type' => Category::class],
                [
                    'title' => 'المدونة',
                    'reference_id' => 4,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'تخطيط شبكي', 'reference_id' => 9, 'reference_type' => Page::class],
                        ['title' => 'تخطيط قائمة', 'reference_id' => 7, 'reference_type' => Page::class],
                        ['title' => 'تخطيط كبير', 'reference_id' => 8, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'المعارض', 'url' => '/galleries'],
                ['title' => 'اتصل بنا', 'reference_id' => 5, 'reference_type' => Page::class],
            ],
        ];

        $viData = [
            'name' => 'Menu chính',
            'slug' => 'main-menu-vi',
            'location' => 'main-menu',
            'items' => [
                [
                    'title' => 'Trang chủ',
                    'url' => '/',
                    'icon_font' => 'elegant-icon icon_house_alt mr-5',
                    'children' => [
                        ['title' => 'Trang chủ mặc định', 'url' => '/'],
                        ['title' => 'Trang chủ 2', 'reference_id' => 2, 'reference_type' => Page::class],
                        ['title' => 'Trang chủ 3', 'reference_id' => 3, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Du lịch', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Điểm đến', 'reference_id' => 4, 'reference_type' => Category::class],
                ['title' => 'Khách sạn', 'reference_id' => 6, 'reference_type' => Category::class],
                ['title' => 'Phong cách sống', 'reference_id' => 9, 'reference_type' => Category::class],
                [
                    'title' => 'Blog',
                    'reference_id' => 4,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Bố cục lưới', 'reference_id' => 9, 'reference_type' => Page::class],
                        ['title' => 'Bố cục danh sách', 'reference_id' => 7, 'reference_type' => Page::class],
                        ['title' => 'Bố cục lớn', 'reference_id' => 8, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Thư viện ảnh', 'url' => '/galleries'],
                ['title' => 'Liên hệ', 'reference_id' => 5, 'reference_type' => Page::class],
            ],
        ];

        $frData = [
            'name' => 'Menu principal',
            'slug' => 'main-menu-fr',
            'location' => 'main-menu',
            'items' => [
                [
                    'title' => 'Accueil',
                    'url' => '/',
                    'icon_font' => 'elegant-icon icon_house_alt mr-5',
                    'children' => [
                        ['title' => 'Accueil par défaut', 'url' => '/'],
                        ['title' => 'Accueil 2', 'reference_id' => 2, 'reference_type' => Page::class],
                        ['title' => 'Accueil 3', 'reference_id' => 3, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Voyage', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Destination', 'reference_id' => 4, 'reference_type' => Category::class],
                ['title' => 'Hôtels', 'reference_id' => 6, 'reference_type' => Category::class],
                ['title' => 'Mode de vie', 'reference_id' => 9, 'reference_type' => Category::class],
                [
                    'title' => 'Blog',
                    'reference_id' => 4,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Grille', 'reference_id' => 9, 'reference_type' => Page::class],
                        ['title' => 'Liste', 'reference_id' => 7, 'reference_type' => Page::class],
                        ['title' => 'Grand format', 'reference_id' => 8, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Galeries', 'url' => '/galleries'],
                ['title' => 'Contact', 'reference_id' => 5, 'reference_type' => Page::class],
            ],
        ];

        $idData = [
            'name' => 'Menu Utama',
            'slug' => 'main-menu-id',
            'location' => 'main-menu',
            'items' => [
                [
                    'title' => 'Beranda',
                    'url' => '/',
                    'icon_font' => 'elegant-icon icon_house_alt mr-5',
                    'children' => [
                        ['title' => 'Beranda default', 'url' => '/'],
                        ['title' => 'Beranda 2', 'reference_id' => 2, 'reference_type' => Page::class],
                        ['title' => 'Beranda 3', 'reference_id' => 3, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Perjalanan', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Destinasi', 'reference_id' => 4, 'reference_type' => Category::class],
                ['title' => 'Hotel', 'reference_id' => 6, 'reference_type' => Category::class],
                ['title' => 'Gaya Hidup', 'reference_id' => 9, 'reference_type' => Category::class],
                [
                    'title' => 'Blog',
                    'reference_id' => 4,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Tata letak grid', 'reference_id' => 9, 'reference_type' => Page::class],
                        ['title' => 'Tata letak daftar', 'reference_id' => 7, 'reference_type' => Page::class],
                        ['title' => 'Tata letak besar', 'reference_id' => 8, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Galeri', 'url' => '/galleries'],
                ['title' => 'Kontak', 'reference_id' => 5, 'reference_type' => Page::class],
            ],
        ];

        $trData = [
            'name' => 'Ana Menü',
            'slug' => 'main-menu-tr',
            'location' => 'main-menu',
            'items' => [
                [
                    'title' => 'Ana Sayfa',
                    'url' => '/',
                    'icon_font' => 'elegant-icon icon_house_alt mr-5',
                    'children' => [
                        ['title' => 'Varsayılan Ana Sayfa', 'url' => '/'],
                        ['title' => 'Ana Sayfa 2', 'reference_id' => 2, 'reference_type' => Page::class],
                        ['title' => 'Ana Sayfa 3', 'reference_id' => 3, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Seyahat', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Destinasyon', 'reference_id' => 4, 'reference_type' => Category::class],
                ['title' => 'Oteller', 'reference_id' => 6, 'reference_type' => Category::class],
                ['title' => 'Yaşam Tarzı', 'reference_id' => 9, 'reference_type' => Category::class],
                [
                    'title' => 'Blog',
                    'reference_id' => 4,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Izgara düzeni', 'reference_id' => 9, 'reference_type' => Page::class],
                        ['title' => 'Liste düzeni', 'reference_id' => 7, 'reference_type' => Page::class],
                        ['title' => 'Büyük düzen', 'reference_id' => 8, 'reference_type' => Page::class],
                    ],
                ],
                ['title' => 'Galeriler', 'url' => '/galleries'],
                ['title' => 'İletişim', 'reference_id' => 5, 'reference_type' => Page::class],
            ],
        ];

        $this->createMenuWithTranslations($enData, $arData, $viData, $frData, $idData, $trData);
    }

    protected function createQuickLinksMenu(): void
    {
        $enData = [
            'name' => 'Quick links',
            'slug' => 'quick-links',
            'items' => [
                ['title' => 'Homepage', 'url' => '/'],
                ['title' => 'Contact', 'reference_id' => 5, 'reference_type' => Page::class],
                ['title' => 'Blog', 'reference_id' => 4, 'reference_type' => Page::class],
                ['title' => 'Travel', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Galleries', 'url' => '/galleries'],
            ],
        ];

        $arData = [
            'name' => 'روابط سريعة',
            'slug' => 'quick-links-ar',
            'items' => [
                ['title' => 'الصفحة الرئيسية', 'url' => '/'],
                ['title' => 'اتصل بنا', 'reference_id' => 5, 'reference_type' => Page::class],
                ['title' => 'المدونة', 'reference_id' => 4, 'reference_type' => Page::class],
                ['title' => 'سفر', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'المعارض', 'url' => '/galleries'],
            ],
        ];

        $viData = [
            'name' => 'Liên kết nhanh',
            'slug' => 'quick-links-vi',
            'items' => [
                ['title' => 'Trang chủ', 'url' => '/'],
                ['title' => 'Liên hệ', 'reference_id' => 5, 'reference_type' => Page::class],
                ['title' => 'Blog', 'reference_id' => 4, 'reference_type' => Page::class],
                ['title' => 'Du lịch', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Thư viện ảnh', 'url' => '/galleries'],
            ],
        ];

        $frData = [
            'name' => 'Liens rapides',
            'slug' => 'quick-links-fr',
            'items' => [
                ['title' => 'Accueil', 'url' => '/'],
                ['title' => 'Contact', 'reference_id' => 5, 'reference_type' => Page::class],
                ['title' => 'Blog', 'reference_id' => 4, 'reference_type' => Page::class],
                ['title' => 'Voyage', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Galeries', 'url' => '/galleries'],
            ],
        ];

        $idData = [
            'name' => 'Tautan cepat',
            'slug' => 'quick-links-id',
            'items' => [
                ['title' => 'Beranda', 'url' => '/'],
                ['title' => 'Kontak', 'reference_id' => 5, 'reference_type' => Page::class],
                ['title' => 'Blog', 'reference_id' => 4, 'reference_type' => Page::class],
                ['title' => 'Perjalanan', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Galeri', 'url' => '/galleries'],
            ],
        ];

        $trData = [
            'name' => 'Hızlı bağlantılar',
            'slug' => 'quick-links-tr',
            'items' => [
                ['title' => 'Ana Sayfa', 'url' => '/'],
                ['title' => 'İletişim', 'reference_id' => 5, 'reference_type' => Page::class],
                ['title' => 'Blog', 'reference_id' => 4, 'reference_type' => Page::class],
                ['title' => 'Seyahat', 'reference_id' => 2, 'reference_type' => Category::class],
                ['title' => 'Galeriler', 'url' => '/galleries'],
            ],
        ];

        $this->createMenuWithTranslations($enData, $arData, $viData, $frData, $idData, $trData);
    }

    protected function createMenuWithTranslations(array $enData, array $arData, array $viData, array $frData, array $idData, array $trData): void
    {
        $menuOrigin = md5(Str::random(20) . time());
        $locationOrigin = md5(Str::random(20) . time() . 'loc');

        $this->createMenu($enData, 'en_US', $menuOrigin, $locationOrigin);
        $this->createMenu($arData, 'ar', $menuOrigin, $locationOrigin);
        $this->createMenu($viData, 'vi', $menuOrigin, $locationOrigin);
        $this->createMenu($frData, 'fr', $menuOrigin, $locationOrigin);
        $this->createMenu($idData, 'id', $menuOrigin, $locationOrigin);
        $this->createMenu($trData, 'tr', $menuOrigin, $locationOrigin);
    }

    protected function createMenu(array $data, string $locale, string $menuOrigin, string $locationOrigin): MenuModel
    {
        $menu = MenuModel::query()->create([
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);

        if (isset($data['location'])) {
            $menuLocation = MenuLocation::query()->create([
                'menu_id' => $menu->getKey(),
                'location' => $data['location'],
            ]);

            if (is_plugin_active('language')) {
                LanguageMeta::saveMetaData($menuLocation, $locale, $locationOrigin);
            }
        }

        foreach ($data['items'] as $position => $menuNode) {
            $this->createMenuNode($position, $menuNode, $menu->getKey());
        }

        if (is_plugin_active('language')) {
            LanguageMeta::saveMetaData($menu, $locale, $menuOrigin);
        }

        return $menu;
    }

    protected function createMenuNode(int $position, array $menuNode, int|string $menuId, int|string $parentId = 0): void
    {
        $menuNode['menu_id'] = $menuId;
        $menuNode['parent_id'] = $parentId;
        $menuNode['position'] = $position;

        if (isset($menuNode['url'])) {
            $menuNode['url'] = str_replace(url(''), '', $menuNode['url']);
        }

        $children = [];
        if (Arr::has($menuNode, 'children') && ! empty($menuNode['children'])) {
            $children = $menuNode['children'];
            $menuNode['has_child'] = true;
        } else {
            $menuNode['has_child'] = false;
        }

        Arr::forget($menuNode, 'children');

        $createdNode = MenuNode::query()->create($menuNode);

        foreach ($children as $childPosition => $child) {
            $this->createMenuNode($childPosition, $child, $menuId, $createdNode->getKey());
        }
    }
}
