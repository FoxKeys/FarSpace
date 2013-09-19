<?php class scriptFMessages extends baseBlock {
public function render() { ?>
<script>
	farSpace.registerModule(
		'f.messages',
		{
			'alert': function (message) {
				var result = $.Deferred();
				setTimeout(function () {
					alert(message);
					result.resolve(true);
				}, 0);
				return result.promise();
			},
			'confirm': function (message) {
				var result = $.Deferred();
				setTimeout(function () {
					if (confirm(message)) {
						result.resolve(true);
					} else {
						result.reject();
					}
				}, 0);
				return result.promise();
			}
		}
	);
</script>
<?php } }
