<?php
$actual = basename($_SERVER['SCRIPT_NAME']);
function isActive($file) {
    global $actual;
    return $actual === $file ? 'nav-current' : '';
}
?>
<nav class="navigation text-center text-white space-x-4">
  <a class="<?=isActive('calendario.php')?>" href="calendario.php">Calendario</a>
  <a class="<?=isActive('alumnos.php')?>"    href="alumnos.php">Alumnos</a>
  <a class="<?=isActive('configuracion.php')?>" href="configuracion.php">Configuración</a>
</nav>