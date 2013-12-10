var plugins = [
    'core',
    'touch-handler',

    'accordion',
    'button-set',
    'carousel',
    'countdown',
    'dropdown',
    'input-control',
    'live-tile',
    //'drag-tile',
    'progressbar',
    'rating',
    'slider',
    'tab-control',
    'table',
    'times',
    'dialog',
    'notify',
    'listview',
    'treeview',
    'fluentmenu',
    'hint',
    'streamer'


];

$.each(plugins, function(i, plugin){
    $("<script/>").attr('src', 'metro/js/metro/metro-'+plugin+'.js').appendTo($('head'));
});
