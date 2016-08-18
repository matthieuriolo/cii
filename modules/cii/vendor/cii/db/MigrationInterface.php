<?php

namespace cii\db;

interface MigrationInterface extends UpgradeInterface
{
    public function down();
}
