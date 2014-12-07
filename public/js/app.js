require.config({
    baseUrl: "/js/",
    paths: {
        jquery          : 'libs/jquery.min',
        bootstrap       : 'libs/bootstrap',
        underscore      : 'libs/node_modules/underscore/underscore',
        backbone        : 'libs/node_modules/backbone/backbone',
        mustache        : 'libs/node_modules/mustache/mustache'
    },
    shim: {
        underscore: {
            exports: "_"
        },
        backbone: {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        }
    }
});

require([
    'jquery',
    'backbone',
    'views/BaseView',
    'views/GameView',
    'models/GameModel',
], function($, Backbone, BaseView, GameView, GameModel) {

    var AppRouter = Backbone.Router.extend({
        routes:
        {
                "":"index",
            "games":"listGame",
            "games/:id" :"viewGame"
        },
        initialize: function(options)
        {
            this.gameModule = options.gameModule;
        },
        index:function()
        {
        },
        listGame:function()
        {
            this.gameModule.page = 0;
            this.gameModule.render();
        },
        viewGame:function(id)
        {
            this.gameModule.viewGame(id);
        }
    });

    $(document).on("click", "a[href]:not([data-bypass])", function(e){
        e.preventDefault();
        e.stopPropagation();

        var href = $(this).attr("href");
        Backbone.history.navigate(href, true);
    });

    $(document).on("click", "[data-toggle='view']", function(e)
    {
        e.preventDefault();
        e.stopPropagation();

        var self = $(this);
        var href = self.attr('data-target') || self.attr('href');

        Backbone.history.navigate(href, true);
    });


    var GameList = new GameView.GameList({
        el             : $('[data-role="main"]'),
        collection     : new GameModel.GameCollection(),
        gameView       : GameView.GameDetails,
        perPage        : 20,
        page           : 0,
        infiniteScroll : true
    });

    new AppRouter({
        gameModule:GameList
    });

    if (typeof window.silentRouter === 'undefined') window.silentRouter = false;

    Backbone.history.start({ pushState: true, root: '/', silent: window.silentRouter });
});