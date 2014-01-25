  jQuery(function() {
        jQuery('#cropbox-mini').Jcrop({
            minSize:    [ 135, 135 ],
            sideHandles: false,
            aspectRatio: 1,
            setSelect: [ 0, 0, 135, 135 ],
            onSelect: updateCoords
        });
        jQuery('#cropbox-small').Jcrop({
            minSize:    [ 180, 180 ],
            sideHandles: false,
            aspectRatio: 1,
            setSelect: [ 0, 0, 180, 180 ],
            onSelect: updateCoords
        });
    });
	function updateCoords(c)
	{
		jQuery('#x').val(c.x);
		jQuery('#y').val(c.y);
		jQuery('#w').val(c.w);
		jQuery('#h').val(c.h);
	};