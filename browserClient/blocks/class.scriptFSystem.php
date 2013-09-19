<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 08.09.2013 11:20
	 */
class scriptFSystem extends baseBlock { public function render() { ?>
<script>
	farSpace.registerModule(
		'f.system',
		{
			'show': function (idSystem, idPlanet) {
				farSpace.load('blockSystemDialog', {idSystem: idSystem, idPlanet: idPlanet}, $());
			}
		}
	);
</script>
<?php } }