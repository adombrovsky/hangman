define([
    'jquery',
    'backbone',
    'views/BaseView',
    'models/GameModel',
], function($, Backbone, BaseView, GameModel)
{
    var $window, $document;

    $window   = $(window);
    $document = $(document);

    var GameDetails = BaseView.extend({
        view:'games.view',
        initialize: function(options)
        {
            this.options = options;
            this.listenTo(this.model, 'change:tries_left', this.render);
            this.listenTo(this.model, 'change:word', this.render);
            this.listenTo(this.model, 'change:status', this.render);
        },
        render:function()
        {
            var self = this;
            if (self.model.get('status') !== 'busy')
            {
                self.model.set('finished',1);
            }
            self.$el.html(self.template(self.model.attributes));
            if (!self.model.get('finished'))
            {
                self.$el.append((new GameForm({model:self.model})).render().el);
            }
            return this;
        }
    });

    var GameForm = BaseView.extend({
        view:'games._form',
        events:{
            "click #guess_letter":"guessLetter"
        },
        render:function()
        {
            var self = this;
            self.$el.html(this.template(self.model.attributes));
            return this;
        },
        guessLetter:function(e)
        {
            var $el = $(e.target);
            var data = $el.closest('form').serializeArray();
            var self = this;
            $(".error_message").hide();
            $('.form-group').removeClass("has-error");
            $.ajax({
                type:'POST',
                url:'/api/games/'+this.model.get('id'),
                data:data,
                success:function(response)
                {
                    self.model.set('status',response.status);
                    self.model.set('word',response.word);
                    self.model.set('tries_left',response.tries_left);
                },
                error:function(response)
                {
                    self.highlightErrors(JSON.parse(response.responseText).errors);
                }
            });
        },
        highlightErrors:function(errors)
        {
            if (typeof errors !== 'undefined')
            {
                for (var a in errors)
                {
                    if (errors.hasOwnProperty(a))
                    {
                        $("#"+a).closest('.form-group').addClass("has-error");
                        $(".error_"+a).text(errors[a][0]).fadeIn();
                    }
                }
            }
        },
        cleanErrors:function()
        {
            this.$el.find('.form-group').removeClass("has-error");
        }
    });

    var GameItem = BaseView.extend({
        view:'games._game',
        tagName:'tr',
        render:function()
        {
            if (this.model.get('status') !== 'busy')
            {
                this.model.set('finished',1);
            }
            this.$el.html(this.template(this.model.attributes));
            return this;
        }
    });
    var GameTable = BaseView.extend({
        view:'games._table',
        render:function()
        {
            this.$el.empty();
            this.$el.html(this.template());
            return this;
        }
    });

    var GameList = BaseView.extend({
        view:'games.index',
        events:{
            "click #create_game" : "createGame"
        },
        initialize: function(options)
        {
            this.options = options;
            this.perPage = this.options.perPage || 150;
            this.page = this.options.page || 0;
            this.fetching = this.collection.fetch();
            if(this.options.infiniteScroll) this.enableInfiniteScroll();
        },
        render:function()
        {
            var self = this;
            self.$el.html('');
            self.$el.html(this.template());
            self.$table = $(new GameTable().render().el);
            self.fetching.done(function(){
                self.addGames();
                self.$el.append(self.$table);
                if(self.options.infiniteScroll) self.enableInfiniteScroll();
            });
        },
        paginate: function()
        {
            var games;
            games = this.collection.rest(this.perPage*this.page);
            games = _.first(games,this.perPage);
            this.page ++;
            return games;
        },
        infiniteScroll: function()
        {
            if($window.scrollTop() >= $document.height() - $window.height() - 50)
            {
                this.addGames();
            }
        },
        enableInfiniteScroll: function()
        {
            var self = this;

            $window.on('scroll', function()
            {
                self.infiniteScroll();
            });
        },
        disableInifiniteScroll: function()
        {
            $window.off('scroll');
        },
        addGames: function()
        {
            var games = this.paginate();
            for(var i=0;i<games.length;i++)
            {
                this.addOneGame(games[i]);
            }
        },
        addOneGame:function(model)
        {
            var view = new GameItem({model:model});
            this.$table.find('tbody').append(view.render().el);
        },
        viewGame: function(id)
        {
            var self = this;
            this.disableInifiniteScroll();
            self.fetching.done(function(){
                var view = new GameDetails({model:self.collection.get(id)});
                self.$el.html(view.render().el);
            });
        },
        createGame: function()
        {
            var model = new GameModel.GameModel();
            var self = this;
            model.save(model.attributes,{
                success:function(model)
                {
                    self.collection.add(model);
                    Backbone.history.navigate('games/'+model.get('id'),true);
                }
            });
        }
    });


    return {
        GameDetails:GameDetails,
        GameItem:GameItem,
        GameList:GameList
    };
});