<?php
namespace Drupal\debug_log;

use Stephane888\Debug\DebugWbu;

/**
 *
 * Provides many helper methods.
 *
 * @ingroup utility
 */
class debugLog {

  /**
   *
   * @param int $data
   * @param int $filename
   * @param string $use
   *          'dump' or 'kint'
   */
  public static function logs($data, $filename = null, $use = 'dump', $auto = false, $code = "")
  {
    if (! $filename) {
      $filename = 'debug';
    }
    if ($auto) {
      $filename = $filename . rand(1, 999);
    }
    $path_of_module = drupal_get_path('module', 'debug_log'); // path_to_theme(); //drupal_get_path('module', 'revolutionslider');
    $path_of_module = DRUPAL_ROOT . '/' . $path_of_module;
    if (! file_exists($path_of_module . '/files-log')) {
      drupal_set_message('dossier en cour de creation dans :' . $path_of_module);
      if (mkdir($path_of_module . '/files-log', $mode = '0777', $recursive = TRUE)) {
        drupal_set_message(' Dossier OK ');
      } else {
        drupal_set_message(' Echec creation dossier', 'warning');
      }
    }
    $filename = $path_of_module . '/files-log/' . $filename . '.html';
    if ($use == 'kint_custom') {
      ob_start();
      DebugWbu::kint_bug($data);
      $result = ob_get_clean();
    } elseif ($use == 'kint') {
      ob_start();
      \kint($data);
      $result = ob_get_clean();
    } elseif ($use == 'file') {
      $result = $data;
    } else {
      ob_start();
      dump($data);
      if (is_object($data)) {
        dump(get_class_methods($data));
      }
      if (! empty($code)) {
        dump($code);
      }
      $result = ob_get_clean();
    }

    $monfichier = fopen($filename, 'w+');
    fputs($monfichier, $result);
    fclose($monfichier);
  }
}