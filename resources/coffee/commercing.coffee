class App
  init: =>
    console.log 'whats up?'

    new (Morris.Line)(
      element: 'myfirstchart'
      data: [
        {
          week: '2008'
          value: 20
        }
        {
          week: '2009'
          value: 10
        }
        {
          week: '2010'
          value: 5
        }
      ]
      xkey: 'week'
      ykeys: [ 'value' ]
      labels: [ 'Value' ])


$(document).ready ->
  Application = new App()
  Application.init()
