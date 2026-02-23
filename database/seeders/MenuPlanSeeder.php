<?php

namespace Database\Seeders;

use App\Enum\MenuTypeEnum;
use App\Models\Menu;
use App\Models\Plan;
use Carbon\Carbon;
use Database\Seeders\Definitions\PlanTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuPlanSeeder extends BaseSeeder
{
    private Carbon $now;
    private const CHUNK_SIZE = 1000;

    public function __construct()
    {
        $this->now = now();
    }

    public function run(): void
    {
        $this->initialize();

        $planTemplates = PlanTemplate::getDefinitions();
        $items         = [];

        Plan::chunkById(self::CHUNK_SIZE, function ($plans) use (&$items, $planTemplates) {
            foreach ($plans as $plan) {
                $template = $this->findPlanByName($planTemplates, $plan->name);
                if ($template === null) {
                    continue;
                }

                $menuTypeValues = array_map(fn ($menuType) => $menuType->value, $template['menu_types']);
                $menus = Menu::where('shop_id', $plan->shop_id)
                    ->whereIn('type', $menuTypeValues)
                    ->orderBy('sort_order')
                    ->get();

                foreach ($template['menu_types'] as $menuType) {
                    $menu = $menus->firstWhere('type', $menuType->value);
                    if ($menu !== null) {
                        $items[] = [
                            'menu_id'    => $menu->id,
                            'plan_id'    => $plan->id,
                            'created_at' => $this->now,
                            'updated_at' => $this->now,
                        ];
                    }
                }
            }
        });


        $this->insertData('menu_plan', $items);

        $this->finalize('MenuPlanSeeder', [
            'メニュープラン数' . count($items),
        ]);
    }

    private function findPlanByName(array $templates, string $planName): ?array
    {
        foreach ($templates as $template) {
            if (($template['name'] ?? '') === $planName) {
                return $template;
            }
        }
        return null;
    }
}
