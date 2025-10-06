<?php
namespace TMW\SA100\Classes;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Provides keyword clustering helpers.
 */
class Keyword_Engine
{
    /**
     * Build keyword clusters from Serper results.
     *
     * @param array  $results Serper results payload.
     * @param string $seed    Seed keyword.
     *
     * @return array
     */
    public function build_clusters(array $results, $seed)
    {
        $clusters = [
            'primary'   => [],
            'secondary' => [],
            'questions' => [],
        ];

        if (! empty($results['organic'])) {
            foreach ($results['organic'] as $item) {
                if (! empty($item['title'])) {
                    $clusters['primary'][] = sanitize_text_field($item['title']);
                }

                if (! empty($item['snippet'])) {
                    $clusters['secondary'][] = sanitize_text_field($item['snippet']);
                }
            }
        }

        if (! empty($results['relatedSearches'])) {
            foreach ($results['relatedSearches'] as $search) {
                $clusters['questions'][] = sanitize_text_field($search['query']);
            }
        }

        $clusters['primary']   = array_values(array_unique($clusters['primary']));
        $clusters['secondary'] = array_values(array_unique($clusters['secondary']));
        $clusters['questions'] = array_values(array_unique($clusters['questions']));

        return [
            'seed'     => $seed,
            'clusters' => $clusters,
        ];
    }
}
