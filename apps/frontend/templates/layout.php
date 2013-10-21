<!DOCTYPE html>
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php //include_title() ?>
    <title>EW//SS</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
  </head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="#">EdgeWorld</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class="<?php sfContext::getInstance()->getModuleName() == 'common' && print 'active'?>"><a href="<?php echo url_for('common/index') ?>">Настройки</a></li>
						<li class="dropdown <?php sfContext::getInstance()->getModuleName() == 'manifest' && print 'active'?>">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Справочники <b class="caret"></b></a>
							<ul class="dropdown-menu">
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
						<li class="<?php sfContext::getInstance()->getModuleName() == 'equipment' && print 'active'?>"><a href="<?php echo url_for('equipment/index')?>">Снаряжение</a></li>
						<li class="<?php sfContext::getInstance()->getModuleName() == 'force' && print 'active'?>"><a href="<?php echo url_for('force/index')?>">Турниры</a></li>
						<li class="<?php sfContext::getInstance()->getModuleName() == 'map' && print 'active'?>"><a href="<?php echo url_for('@maps')?>">Карты</a></li>
					</ul>
					<p class="navbar-text pull-right">You logged as...</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<?php echo $sf_content ?>
	</div>
	<div class="navbar navbar-inverse navbar-fixed-bottom">
		<div class="navbar-inner">
			<ul class="nav">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">MainBase <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="#">B1</a></li>
						<li><a href="#">B2</a></li>
						<li><a href="#">B3</a></li>
					</ul>
				</li>		
			</ul>
			<style>
				#player-info > span {
					margin-left: 10px;
					margin-right: 10px;
					font-weight: bold;
					font-size: smaller;
				};
			</style>
		<p class="navbar-text pull-right" id="player-info"><span>L: <span id="level-value">300000</span></span> <span>XP: <span id="xp-value">300000000000000</span></span> <span>SP: 30000</span> <span>FP: 300000000000</span> <span style="color: rgb(19,159,157);">C: 200000000</span> <span style="color: rgb(144,20,209)">G: 200000000</span> <span style="color: yellow;">E: 200000000</span> <span style="color: green; width: 250px;">U: 200000000</span> <span style="color: white;">P: 2000</span></p>
		</div>
	</div>
</body>
</html>
