<!DOCTYPE html>
<html class="dark">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    />
    <title>Alumnos</title>
    <link rel="stylesheet" href="css/datatables.min.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/datatables.min.js"></script>
  </head>
  <body class="min-h-screen bg-white dark:bg-gray-800">
    <?php include 'partials/nav.php'; ?>
    <table id="alumnos" class="min-w-full divide-y bg-gray-900">
      <thead class="bg-gray-50">
        <tr>
          <th>ID</th>
          <th>Nombre completo</th>
          <th>Matrícula</th>
          <th>Calendarios</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Ana Isabel Rivas Soto</td>
          <td>A01234567</td>
          <td>2025‑A · 2025‑B</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Bruno Alejandro García León</td>
          <td>B09234511</td>
          <td>2025‑A</td>
        </tr>
        <tr>
          <td>3</td>
          <td>Carolina Méndez Flores</td>
          <td>C07311428</td>
          <td>2025‑B · 2026‑A</td>
        </tr>
        <tr>
          <td>4</td>
          <td>Diego Armando Ruiz Martínez</td>
          <td>D08191234</td>
          <td>2025‑B</td>
        </tr>
        <tr>
          <td>5</td>
          <td>Elena Sofía Treviño Núñez</td>
          <td>E06782345</td>
          <td>2024‑B · 2025‑A</td>
        </tr>
        <tr>
          <td>6</td>
          <td>Fernando Iván Castillo Vega</td>
          <td>F05431192</td>
          <td>2025‑A</td>
        </tr>
        <tr>
          <td>7</td>
          <td>Gabriela del Carmen Lozano</td>
          <td>G04591378</td>
          <td>2025‑A · 2025‑B · 2026‑A</td>
        </tr>
        <tr>
          <td>8</td>
          <td>Héctor Manuel Chávez Torres</td>
          <td>H03328741</td>
          <td>2024‑B</td>
        </tr>
        <tr>
          <td>9</td>
          <td>Isabel Fernanda Salinas Díaz</td>
          <td>I02937485</td>
          <td>2025‑B</td>
        </tr>
        <tr>
          <td>10</td>
          <td>José Luis Zamora Quintana</td>
          <td>J01874296</td>
          <td>2025‑A · 2026‑A</td>
        </tr>
      </tbody>
    </table>

    <script src="js/students.js"></script>
  </body>
</html>
