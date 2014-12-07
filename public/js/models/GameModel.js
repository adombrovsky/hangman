define([
    'jquery',
    'backbone',
], function($, Backbone) {

    var GameCollection = Backbone.Collection.extend({
        url:'/api/games'
    });

    var GameModel = Backbone.Model.extend({
        url:'/api/games'
    });

    return {
        GameCollection:GameCollection,
        GameModel:GameModel,
    };
});