<?php

include_once __DIR__ . '/../../../Controller/dmd_con.php';

$dmdCon = new dmdCon();

// Fetch all ads
$all_ads = $dmdCon->searchdmd('paid', '', 'accepted');

// if BLABLA BLA BLA BLA affiche size kbirr horizontale

foreach ($all_ads as $ad): ?>
	<div class="col-xl-6 col-sm-6 <?= $ad['paid'] === 'payed' ? 'corporate' : 'business' ?>">
		<div class="vertical-item item-gallery content-absolute text-center ds">
			<div class="item-media">
				<!-- Display ad image -->
				<img src="data:image/jpeg;base64,<?= base64_encode($ad['image']) ?>" alt="">
				<div class="media-links">
					<div class="links-wrap">
						<!-- Link to view ad details -->
						<a class="link-zoom photoswipe-link" title=""
							href="data:image/jpeg;base64,<?= base64_encode($ad['image']) ?>"></a>
						<a class="links-infos-dmd"
							href="javascript:displayPopup('<?= $ad['titre'] ?>', '<?= $ad['contenu'] ?>', '<?= $ad['objectif'] ?>', '<?= $ad['dure'] ?>', '<?= $ad['budget'] ?>')"
							title=""><i class="fa fa-info-circle"></i></a>
						<?php if ($ad['paid'] == 'pending') { ?>
							<a class="links-infos-dmd" title="" href="ads_payment.php?iddemande=<?= $ad['iddemande'] ?>"><i
									class="fa fa-credit-card"></i></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="item-content gradientdarken-background">
				<!-- Display ad title -->
				<h4><a href="gallery-single.html"><?= $ad['titre'] ?></a></h4>
				<h5><a><i class="fa fa-coins mr-3"></i><?= $ad['budget'] ?></a></h5>
			</div>
		</div>
	</div>
<?php endforeach; 

// else if BLABLA BLA BLA BLA affiche size sghiir horizontale

foreach ($all_ads as $ad): ?>
	<div class="col-xl-4 col-sm-6 <?= $ad['paid'] === 'payed' ? 'corporate' : 'business' ?>">
		<div class="vertical-item item-gallery content-absolute text-center ds">
			<div class="item-media">
				<!-- Display ad image -->
				<img src="data:image/jpeg;base64,<?= base64_encode($ad['image']) ?>" alt="">
				<div class="media-links">
					<div class="links-wrap">
						<!-- Link to view ad details -->
						<a class="link-zoom photoswipe-link" title=""
							href="data:image/jpeg;base64,<?= base64_encode($ad['image']) ?>"></a>
						<a class="links-infos-dmd"
							href="javascript:displayPopup('<?= $ad['titre'] ?>', '<?= $ad['contenu'] ?>', '<?= $ad['objectif'] ?>', '<?= $ad['dure'] ?>', '<?= $ad['budget'] ?>')"
							title=""><i class="fa fa-info-circle"></i></a>
						<?php if ($ad['paid'] == 'pending') { ?>
							<a class="links-infos-dmd" title="" href="ads_payment.php?iddemande=<?= $ad['iddemande'] ?>"><i
									class="fa fa-credit-card"></i></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="item-content gradientdarken-background">
				<!-- Display ad title -->
				<h4><a href="gallery-single.html"><?= $ad['titre'] ?></a></h4>
				<h5><a><i class="fa fa-coins mr-3"></i><?= $ad['budget'] ?></a></h5>
			</div>
		</div>
	</div>
<?php endforeach; 

// else if lbeqi fel aqal -_- -_- -_- -_- -_- -_- -_-

?>