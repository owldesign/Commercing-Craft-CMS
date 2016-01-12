var App,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

App = (function() {
  function App() {
    this.init = bind(this.init, this);
  }

  App.prototype.init = function() {
    console.log('whats up?');
    return new Morris.Line({
      element: 'myfirstchart',
      data: [
        {
          week: '2008',
          value: 20
        }, {
          week: '2009',
          value: 10
        }, {
          week: '2010',
          value: 5
        }
      ],
      xkey: 'week',
      ykeys: ['value'],
      labels: ['Value']
    });
  };

  return App;

})();

$(document).ready(function() {
  var Application;
  Application = new App();
  return Application.init();
});
