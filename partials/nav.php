<?php
$actual = basename($_SERVER['SCRIPT_NAME']);
function isActive($file) {
    global $actual;
    return $actual === $file ? 'nav-current' : '';
}
?>
<nav class="navigation text-center text-white space-x-4">
  <a class="<?=isActive('index.php')?>" href="index.php">Inicio</a>
  <a class="<?=isActive('alumnos.php')?>"    href="alumnos.php">Alumnos</a>
  <a class="<?=isActive('calendarios.php')?>"    href="calendarios.php">Calendarios</a>
  <a class="<?=isActive('asignacion.php')?>" href="asignacion.php">Asignación</a>
</nav>