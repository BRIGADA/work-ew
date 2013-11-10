<!DOCTYPE html>
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php //include_title() ?>
    <title>EW//SS</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo url_for('@homepage') ?>">EW//SS</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="<?php if (sfContext::getInstance()->getModuleName() == 'common') : ?>active<?php endif ?>">
              <a href="<?php echo url_for('common/index') ?>">Настройки</a>
            </li>
            <li class="dropdown <?php if (sfContext::getInstance()->getModuleName() == 'manifest') : ?>active<?php endif ?>">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">Справочники <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo url_for('manifest/index') ?>">Главная</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo url_for('@manifest-buildings') ?>">Здания</a></li>
                <li><a href="<?php echo url_for('@manifest-items') ?>">Предметы</a></li>
                <li><a href="<?php echo url_for('@manifest-units') ?>">Юниты</a></li>
                <li><a href="<?php echo url_for('@manifest-campaigns') ?>">Компании</a></li>
                <li><a href="<?php echo url_for('@manifest-generals') ?>">Генералы</a></li>
                <li><a href="<?php echo url_for('@manifest-skills') ?>">Скилы</a></li>
                <li><a href="<?php echo url_for('@manifest-researches') ?>">Исследования</a></li>
                <li><a href="<?php echo url_for('@manifest-defenses') ?>">Оборона</a></li>
                <li><a href="<?php echo url_for('@manifest-equipments') ?>">Снаряжение</a></li>
                <li><a href="<?php echo url_for('@manifest-recipes') ?>">Рецепты</a></li>
              </ul>
            </li>
            <li class="<?php if (sfContext::getInstance()->getModuleName() == 'equipment') : ?>active<?php endif ?>">
              <a href="<?php echo url_for('equipment/index') ?>">Снаряжение</a>
            </li>
            <li class="<?php if (sfContext::getInstance()->getModuleName() == 'force') : ?>active<?php endif ?>">
              <a href="<?php echo url_for('force/index') ?>">Турниры</a>
            </li>
            <li class="<?php if (sfContext::getInstance()->getModuleName() == 'map') : ?>active<?php endif ?>">
              <a href="<?php echo url_for('@maps') ?>">Карты</a>
            </li>
            <li class="<?php if (sfContext::getInstance()->getModuleName() == 'chat') : ?>active<?php endif ?>">
              <a href="<?php echo url_for('chat/index') ?>">Чат</a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
      <?php echo $sf_content ?>
    </div>

    <div class="navbar navbar-inverse navbar-fixed-bottom">
      <div class="container">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">MainBase <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#">B1</a></li>
              <li><a href="#">B2</a></li>
              <li><a href="#">B3</a></li>
            </ul>
          </li>		
        </ul>
        <?php if ($sf_user->getClient()) : ?>
          <p class="navbar-text pull-right" id="player-info">
            <span style="margin-right: 10px;">L: <span id="client-level"><?php echo $sf_user->getClientValue('level') ?></span></span>
            <span style="margin-right: 10px;">XP: <span id="client-xp" style="color: white; box-shadow: 0px 0px 3px red"><?php echo $sf_user->getClientXP() ?></span></span>
            <span style="margin-right: 10px;">SP: <span id="client-sp"><?php echo $sf_user->getClientValue('sp') ?></span></span>
            <span style="margin-right: 10px;">FP: <span id="client-fp"><?php echo $sf_user->getClientValue('fp') ?></span></span>
            <span>P: <span id="client-platinum"><?php echo $sf_user->getClientValue('plat') ?></span></span>
          </p>
        <?php endif ?>
      </div>
    </div>
  </body>
</html>
