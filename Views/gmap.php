<script type="text/javascript">
    $(function(){
        gmaps['<?php echo $instance->getId() ?>'] = new Gmap(<?= json_encode($instance->getOptions()) ?>);

        gmaps['<?php echo $instance->getId(); ?>'].initialize(function() {
<? foreach ($markers as $marker): ?>
                gmaps['<?php echo $instance->getId(); ?>'].addMarker(<?= $marker ?>);
<? endforeach; ?>

<? foreach ($polylines as $polyline): ?>
                gmaps['<?php echo $instance->getId(); ?>'].addPolyline(<?= $polyline ?>);
<? endforeach; ?>

<? foreach ($polygons as $polygon): ?>
                gmaps['<?php echo $instance->getId(); ?>'].addPolygon(<?= $polygon ?>);
<? endforeach; ?>

<? foreach ($geocode_requests as $gcrequest): ?>
                gmaps['<?php echo $instance->getId(); ?>'].geocode(<?= $gcrequest ?>);
<? endforeach; ?>
        });
    });
</script>

<div id="gmap_<?php echo $instance->getId(); ?>"
     style="width:<?php echo $instance->getWidth(); ?>; height:<?php echo $instance->getHeight(); ?>">
</div>