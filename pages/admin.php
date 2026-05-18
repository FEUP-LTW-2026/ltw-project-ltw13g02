<?php 
  declare(strict_types=1);

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/admin.php');

  // Passing 'admin.css' since we will add specific styling there
  drawHeader('Admin', ['admin.css']);
?>

<!-- 
  Dados importantes para o admin:
  - secção de gestão de contas (ativar contas de trainers e clientes, elevar users / trainers a admin)
  - gestão do catálogo de array_count_values
  - gestão de equipamento (adicionar, remover, atualizar estado)
  - gestão do ginásio geral (definir horários de trainers, número de membros no mês, atualizar preço de memberships)
  - pode ser necessário código assincrono (ajax + php)

  I want you to complete the other sections of admin, writing the html and css for that. Adhere to our coding styling and standards, check all css files, because some of them define important variables.
  Manage members and trainers (create, update, and deactivate accounts).
Manage the class catalog (create, edit, and remove classes) and assign trainers to them.
Manage equipment in the main training area (add, update availability status, and remove items).
Elevate a user to admin status.
Oversee and ensure the smooth operation of the entire system.
-->
<?php 
  drawIntro('ADMIN DASHBOARD', 'Manage your gym with ease', 'From equipment to classes, control everything in one place.');
  drawAdminPanel();
?>

<?php drawFooter() ?>