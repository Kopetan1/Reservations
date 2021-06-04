<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>HTML5 Бронювання кімнат в готелі (JavaScript/PHP/MySQL)</title>
  <!-- допоміжні бібліотеки -->
  <script src="https://code.jquery.com/jquery-1.9.1.min.js" integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ=" crossorigin="anonymous"></script>
  <!-- бібліотека daypilot -->
  <script src="js/daypilot-all.min.js" type="text/javascript"></script>
</head>
<style>
  .scheduler_default_rowheader_inner {
    border-right: 1px solid #ccc;
  }

  .scheduler_default_rowheader.scheduler_default_rowheadercol2 {
    background: #fff;
  }

  .scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner {
    top: 2px;
    bottom: 2px;
    left: 2px;
    background-color: transparent;
    border-left: 5px solid #1a9d13;
    /* green */
    border-right: 0px none;
  }

  .status_dirty.scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner {
    border-left: 5px solid #ea3624;
    /* red */
  }

  .status_cleanup.scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner {
    border-left: 5px solid #f9ba25;
    /* orange */
  }
</style>

<body>
  <header>
    <div class="bg-help">
      <div class="inBox">
        <h1 id="logo">HTML5 Бронювання кімнат в готелі (JavaScript/PHP)</h1>
        <p id="claim">AJAX'овий Календар-застосунок з JavaScript/HTML5/jQuery</p>
        <hr class="hidden" />
      </div>
    </div>
  </header>
  <main>
      Show rooms: 
    <select id="filter">
        <option value="0">All</option>
        <option value="1">Single</option>
        <option value="2">Double</option>
        <option value="4">Family</option>
    </select>

    <script>
      $(document).ready(function() {
        $("#filter").change(function() {
            loadResources();
        });
      });
    </script>
    <div style="">
      <div id="dp"></div>
      <script>
        function loadResources() {
          $.post("include/backend_rooms.php",
            function(data) {
              dp.resources = data;
              dp.update();
            });
        }

        function loadEvents() {
          var start = dp.visibleStart();
          var end = dp.visibleEnd();

          $.post("include/backend_events.php", {
              start: start.toString(),
              end: end.toString()
            },
            function(data) {
              dp.events.list = data;
              dp.update();
            }
          );
          dp.onEventMoved = function (args) {
              $.post("backend_move.php", 
              {
                  id: args.e.id(),
                  newStart: args.newStart.toString(),
                  newEnd: args.newEnd.toString(),
                     newResource: args.newResource
              }, 
              function(data) {
                  dp.message(data.message);
              });
            };
            dp.eventDeleteHandling = "Update";

dp.onEventDeleted = function(args) {
  $.post("backend_delete.php", 
  {
      id: args.e.id()
  }, 
  function() {
      dp.message("Deleted.");
  });
};
        }

        

        let dp = new DayPilot.Scheduler("dp");
        dp.startDate = DayPilot.Date.today().firstDayOfMonth(); //буде показуватися з першого дня поточного місяця
        dp.days = DayPilot.Date.today().daysInMonth();
        dp.scale = "Day"; //показувати тільки днями
        dp.timeHeaders = [ //налаштовуємо формат виводу заголовку
          {
            groupBy: "Month",
            format: "MMMM yyyy"
          },
          {
            groupBy: "Day",
            format: "d"
          }
        ];
        dp.rowHeaderColumns = [{
            title: "Room",
            width: 80
          },
          {
            title: "Capacity",
            width: 80
          },
          {
            title: "Status",
            width: 80
          }
        ];
        dp.onBeforeResHeaderRender = function(args) {
          var beds = function(count) {
            return count + " bed" + (count > 1 ? "s" : "");
          };
          args.resource.columns[0].html = args.resource.name;

          args.resource.columns[1].html = beds(args.resource.capacity);
          args.resource.columns[2].html = args.resource.status;
          switch (args.resource.status) {
            case "Dirty":
              args.resource.cssClass = "status_dirty";
              break;
            case "Cleanup":
              args.resource.cssClass = "status_cleanup";
              break;
          }
        };
        
        dp.onTimeRangeSelected = function(args) {

          var modal = new DayPilot.Modal();
          modal.closed = function() {
            dp.clearSelection();

            var data = this.result;
            if (data && data.result === "OK") {
              loadEvents();
            }
          };
          modal.showUrl("new.php?start=" + args.start + "&end=" + args.end + "&resource=" + args.resource);

        };

        dp.onEventClick = function(args) {
          var modal = new DayPilot.Modal();
          modal.closed = function() {
            // reload all events
            var data = this.result;
            if (data && data.result === "OK") {
              loadEvents();
            }
          };
          modal.showUrl("edit.php?id=" + args.e.id());
        };

        dp.allowEventOverlap = false;
        dp.init();

        loadResources()
        loadEvents()
      </script>
    </div>
  </main>
  <br />
  <footer>
    <address>(с)Автор лабораторної роботи: студент спеціальності ІПЗ-20004м, Жежера Володимир</address>
  </footer>
</body>

</html>