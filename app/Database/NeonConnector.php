<?php

namespace App\Database;

use Illuminate\Database\Connectors\PostgresConnector;

class NeonConnector extends PostgresConnector
{
    public function getOptions(array $config)
    {
        $options = $config['options'] ?? [];

        if (! is_array($options)) {
            $options = [];
        }

        return array_diff_key($this->options, $options) + $options;
    }

    protected function getDsn(array $config): string
    {
        $dsn = parent::getDsn($config);

        if (isset($config['neon_endpoint'])) {
            $dsn .= ";options=endpoint=" . $config['neon_endpoint'];
        }

        return $dsn;
    }
}