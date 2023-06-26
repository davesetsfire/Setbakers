<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Attribute;
use App\Models\AttributeOption;

class AddAttributesOptions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $attributes = [
            [
                'label' => 'color',
                'type' => 'color_select',
                'options' => ['FFFF00' => 'gelb', 'ff0000' => 'rot', '8fff00' => 'grün', '0085ff' => 'blau', '800080' => 'lila', 'ffffff' => 'weiß', '474747' => 'grau', '701d0b' => 'braun', '000000' => 'schwarz', 'F5F5DC' => 'beige', 'C0C0C0' => 'silber', 'FFD700' => 'gold']],
            [
                'label' => 'epoche',
                'type' => 'select',
                'options' => ['18.Jahrhundert', '19.Jahrhundert', '10er Jahre', '20er Jahre', '30er Jahre', '40er Jahre', '50er Jahre', '60er Jahre', '70er Jahre', '80er Jahre', '90er Jahre', 'ab 2000', 'ab 2010', 'ab 2020']],
            [
                'label' => 'style',
                'type' => 'select',
                'options' => ['Romantik', 'Gotik', 'Renaissance', 'Barock', 'Rokoko', 'Klassizismus', 'Biedermeier', 'Historismus', 'Jugendstil', 'Art Déco', 'Bauhaus', 'DDR', 'Westdeutschland', 'Mittelalter']
            ],
            [
                'label' => 'radius',
                'type' => 'select',
                'options' => ['10' => '+10 km', '20' => '+20 km', '30' => '+30 km', '40' => '+40 km', '50' => '+50 km', '100' => '+100 km', '150' => '+150 km', '200' => '+200 km']
            ],
            [
                'label' => 'file_format',
                'type' => 'select',
                'options' => ['Pixelgrafik', 'Vektorgrafik']
            ],
            [
                'label' => 'graphic_form',
                'type' => 'select',
                'options' => ['In gedruckter Form', 'Druckauftrag an Druckerei', 'Als digitale Datei']
            ],
            [
                'label' => 'copy_right',
                'type' => 'select',
                'options' => ['Rechte liegen beim Grafiker', 'Repro (Rechte liegen beim Unternehmen des Produkts)']
            ],
            [
                'label' => 'manufacture_country',
                'type' => 'select',
                'options' => ['Deutschland', 'USA', 'Italien', 'Großbritannien', 'Schweden', 'Japan', 'Frankreich', 'DDR', 'China', 'Sonstige']
            ],
            [
                'label' => 'location',
                'type' => 'select',
                'options' => ['Berlin', 'Bayern', 'Baden Württemberg', 'Sachsen', 'Sachsen Anhalt', 'Nordrheinwestfalen', 'Hessen', 'Bremen', 'Schleswig Holstein', 'Mecklenburg Vorpommern', 'Brandenburg', 'Thüringen', 'Hamburg', 'Saarland', 'Rheinlandpfalz', 'Niedersachsen', 'Österreich', 'Schweiz', 'Dänemark', 'Polen', 'Tschechische Republik', 'Niederlande', 'Belgien', 'Luxemburg', 'Frankreich']
            ],
            [
                'label' => 'manufacture',
                'type' => 'select',
                'options' => ['Mercedes Benz', 'Ford', 'Porsche', 'Opel', 'Chevrolet', 'BMW', 'VW', 'Volvo', 'Audi', 'Rolls Royce', 'Bogward', 'Buick', 'Citroen', 'Dacia', 'Dodge', 'Ferrari', 'Honda', 'Hyundai', 'Jaguar', 'Jeep', 'Kia', 'Lada', 'Lamborghini', 'Land Rover', 'Lexus', 'MAN', 'Maserati', 'Mazda', 'Mini', 'Mitsubhishi', 'Nissan', 'Peugeot', 'Pontiac', 'Piaggio', 'Range Rover', 'Renault', 'Tesla', 'Sonstige']
            ],
            [
                'label' => 'duration',
                'type' => 'select',
                'options' => ['Tag', '3 Tage', 'Woche', 'Monat']
            ],
            [
                'label' => 'duration_graphics',
                'type' => 'select',
                'options' => ['Lizenz', 'Stück']
            ]
        ];
                
        foreach ($attributes as $attribute) {
            $attributeMatser = Attribute::create(['type' => $attribute['type'], 'label' => $attribute['label']]);
            foreach ($attribute['options'] as $optionValue => $optionDisplay) {
                AttributeOption::create([
                    'attribute_id' => $attributeMatser->id,
                    'option_value' => in_array($attribute['label'], ['color', 'radius']) ? $optionValue : $optionDisplay,
                    'option_display' => $optionDisplay
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
