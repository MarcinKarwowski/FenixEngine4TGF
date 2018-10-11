<?php

namespace Game\Shema;

use Phalcon\Db\Column as Column;
use Phalcon\Db\Index as Index;
use Phalcon\Db\Reference as Reference;

class LoadDB
{

    public function install($connection, $schema)
    {
        if (!$connection->tableExists("game_cr_categories", $schema))
        {
            $connection->createTable(
                "game_cr_categories",
                null,
                array(
                    "columns" => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => false,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "name",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 70,
                                "notNull" => true,
                                "after"   => "id",
                            )
                        ),
                        new Column(
                            "text",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 2000,
                                "notNull" => true,
                                "after"   => "name",
                            )
                        ),
                        new Column(
                            "orderid",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "text",
                            )
                        ),
                        new Column(
                            "showinprofile",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 1,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "orderid",
                                "default"  => 1
                            )
                        ),
                        new Column(
                            "showincreator",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 1,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "showinprofile",
                                "default"  => 1
                            )
                        ),
                        new Column(
                            "type",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 10,
                                "notNull" => true,
                                "default" => "list",
                                "after"   => "showincreator",
                            )
                        ),
                        new Column(
                            "params",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 400,
                                "notNull" => false,
                                "default" => NULL,
                                "after"   => "type",
                            )
                        ),
                    ),
                    "indexes" => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                    ),
                    "options" => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_cr_pages", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_cr_pages",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "name",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 70,
                                "notNull" => true,
                                "after"   => "id",
                            )
                        ),
                        new Column(
                            "category_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "name",
                            )
                        ),
                        new Column(
                            "wiki_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "category_id",
                                "default"  => 0,
                            )
                        ),
                        new Column(
                            "text",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 2000,
                                "notNull" => true,
                                "after"   => "wiki_id",
                                "default" => '',
                            )
                        ),
                        new Column(
                            "params",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 1000,
                                "notNull" => true,
                                "after"   => "text",
                                "default" => '',
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                    ),
                    "references" => array(
                        new Reference(
                            "page_category_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "game_cr_categories",
                                "columns"           => array("category_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        )
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_cr_relations", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_cr_relations",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "page_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => true,
                                "notNull"  => true,
                                "after"    => "id",
                            )
                        ),
                        new Column(
                            "link_page_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "page_id",
                            )
                        ),
                        new Column(
                            "value",
                            array(
                                "type"    => Column::TYPE_INTEGER,
                                "size"    => 10,
                                "notNull" => true,
                                "after"   => "link_page_id",
                                "default" => 0,
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                        new Index(
                            "page_id",
                            array("page_id")
                        ),
                        new Index(
                            "link_page_id",
                            array("link_page_id")
                        ),
                    ),
                    "references" => array(
                        new Reference(
                            "page_link_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "game_cr_pages",
                                "columns"           => array("page_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        )
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_cr_players", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_cr_players",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "character_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "id",
                            )
                        ),
                        new Column(
                            "page_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => true,
                                "notNull"  => true,
                                "after"    => "character_id",
                            )
                        ),
                        new Column(
                            "value",
                            array(
                                "type"    => Column::TYPE_INTEGER,
                                "size"    => 10,
                                "notNull" => true,
                                "after"   => "page_id",
                                "default" => 0,
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                    ),
                    "references" => array(
                        new Reference(
                            "page_char_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "characters",
                                "columns"           => array("character_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        ),
                        new Reference(
                            "page_page_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "game_cr_pages",
                                "columns"           => array("page_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        )
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_locations", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_locations",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "parent_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => true,
                                "notNull"  => true,
                                "after"    => "character_id",
                            )
                        ),
                        new Column(
                            "name",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 50,
                                "notNull" => true,
                                "after"   => "parent_id",
                                "default" => 'Lokacja',
                            )
                        ),
                        new Column(
                            "text",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 2000,
                                "notNull" => true,
                                "after"   => "name",
                                "default" => '',
                            )
                        ),
                        new Column(
                            "type",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 10,
                                "notNull" => true,
                                "after"   => "text",
                                "default" => 'CONTENT',
                            )
                        ),
                        new Column(
                            "coords",
                            array(
                                "type"    => Column::TYPE_TEXT,
                                "notNull" => false,
                                "after"   => "type",
                                "default" => '',
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                        new Index(
                            "parent_id",
                            array("parent_id")
                        ),
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_items_cat", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_items_cat",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "name",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 50,
                                "notNull" => true,
                                "after"   => "id",
                                "default" => 'Lokacja',
                            )
                        ),
                        new Column(
                            "text",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 2000,
                                "notNull" => true,
                                "after"   => "name",
                                "default" => '',
                            )
                        ),
                        new Column(
                            "type",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 10,
                                "notNull" => true,
                                "after"   => "text",
                                "default" => 'CONTENT',
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_items", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_items",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "category_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => true,
                                "notNull"  => true,
                                "after"    => "id",
                            )
                        ),
                        new Column(
                            "name",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 50,
                                "notNull" => true,
                                "after"   => "category_id",
                                "default" => 'Przedmiot',
                            )
                        ),
                        new Column(
                            "text",
                            array(
                                "type"    => Column::TYPE_VARCHAR,
                                "size"    => 2000,
                                "notNull" => true,
                                "after"   => "name",
                                "default" => '',
                            )
                        ),
                        new Column(
                            "price",
                            array(
                                "type"    => Column::TYPE_INTEGER,
                                "size"    => 10,
                                "notNull" => true,
                                "after"   => "text",
                                "default" => 0,
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                    ),
                    "references" => array(
                        new Reference(
                            "item_cat_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "game_items_cat",
                                "columns"           => array("category_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        )
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_locations_items", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_locations_items",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "location_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => true,
                                "notNull"  => true,
                                "after"    => "id",
                            )
                        ),
                        new Column(
                            "item_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => true,
                                "notNull"  => true,
                                "after"    => "location_id",
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                    ),
                    "references" => array(
                        new Reference(
                            "item_location_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "game_items",
                                "columns"           => array("item_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        ),
                        new Reference(
                            "location_node_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "game_locations",
                                "columns"           => array("location_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        )
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_items_players", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_items_players",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "item_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => true,
                                "notNull"  => true,
                                "after"    => "id",
                            )
                        ),
                        new Column(
                            "character_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "item_id",
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                    ),
                    "references" => array(
                        new Reference(
                            "item_item_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "game_items",
                                "columns"           => array("item_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        ),
                        new Reference(
                            "item_char_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "characters",
                                "columns"           => array("character_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        )
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }

        if (!$connection->tableExists("game_characters_achivements", $schema))
        {
            // Add skils table
            $connection->createTable(
                "game_characters_achivements",
                null,
                array(
                    "columns"    => array(
                        new Column(
                            "id",
                            array(
                                "type"          => Column::TYPE_INTEGER,
                                "size"          => 10,
                                "unsigned"      => true,
                                "notNull"       => true,
                                "autoIncrement" => true,
                                "first"         => true,
                            )
                        ),
                        new Column(
                            "character_id",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "id",
                            )
                        ),
                        new Column(
                            "gain",
                            array(
                                "type"     => Column::TYPE_INTEGER,
                                "size"     => 10,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "character_id",
                            )
                        ),
                        new Column(
                            "type",
                            array(
                                "type"     => Column::TYPE_VARCHAR,
                                "size"     => 10,
                                "notNull"  => true,
                                "after"    => "gain",
                                'default' => 'PD',
                            )
                        ),
                        new Column(
                            "text",
                            array(
                                "type"     => Column::TYPE_VARCHAR,
                                "size"     => 2000,
                                "notNull"  => true,
                                "after"    => "type",
                                'default' => '',
                            )
                        ),
                        new Column(
                            "date",
                            array(
                                "type"     => Column::TYPE_BIGINTEGER,
                                "size"     => 20,
                                "unsigned" => false,
                                "notNull"  => true,
                                "after"    => "text",
                            )
                        ),
                    ),
                    "indexes"    => array(
                        new Index(
                            "PRIMARY",
                            array("id")
                        ),
                    ),
                    "references" => array(
                        new Reference(
                            "achive_char_id",
                            array(
                                "referencedSchema"  => $schema,
                                "referencedTable"   => "characters",
                                "columns"           => array("character_id"),
                                "referencedColumns" => array("id"),
                                "onDelete"          => 'CASCADE',
                                "onUpdate"          => 'NO ACTION',
                            )
                        )
                    ),
                    "options"    => array(
                        "TABLE_TYPE"      => "BASE TABLE",
                        "ENGINE"          => "InnoDB",
                        "TABLE_COLLATION" => "utf8_general_ci",
                    )
                )
            );
        }
    }

    public function uninstall($connection)
    {
        $connection->dropTable("game_characters_achivements");
        $connection->dropTable("game_cr_players");
        $connection->dropTable("game_cr_relations");
        $connection->dropTable("game_cr_pages");
        $connection->dropTable("game_cr_categories");
        $connection->dropTable("game_locations_items");
        $connection->dropTable("game_locations");
        $connection->dropTable("game_items_players");
        $connection->dropTable("game_items");
        $connection->dropTable("game_items_cat");
    }
}