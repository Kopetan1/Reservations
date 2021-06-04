<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Reservation</title>
    <link type="text/css" rel="stylesheet" href="media/layout.css" />
    <!-- допоміжні бібліотеки -->
    <script src="https://code.jquery.com/jquery-1.9.1.min.js" integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ=" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    // перевірка додавання броні
    //is_numeric($_GET['id']) or die("invalid URL");

    require_once './include/db.php';
    $rooms = $db->query('SELECT * FROM rooms');

    $stmt = $db->prepare('SELECT * FROM reservations WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $roomReserv = $stmt->fetch();

    $start = $roomReserv['start'];
    $end = $roomReserv['end'];

    ?>
    <form id="f" action="/include/backend_edit.php" style="padding:20px;">
        <h1>Edit Reservation</h1>

        <input type="hidden" name="id" value="<?= $roomReserv['id'] ?>">
        <div>Status:</div>
        <div>
            <select id="status" name="status">
                <?php
                $options = array("New", "Confirmed", "Arrived", "CheckedOut");
                foreach ($options as $option) {
                    $selected = $option == $reservation['status'] ? ' selected="selected"' : '';
                    $id = $option;
                    $name = $option;
                    print "<option value='$id' $selected>$name</option>";
                }
                ?>
            </select>
        </div>
        <div>Paid:</div>
        <div>
            <select id="paid" name="paid">
                <?php
                $options = array(0, 50, 100);
                foreach ($options as $option) {
                    $selected = $option == $reservation['paid'] ? ' selected="selected"' : '';
                    $id = $option;
                    $name = $option . "%";
                    print "<option value='$id' $selected>$name</option>";
                }
                ?>
            </select>

        </div>

        <div>Name: </div>
        <div><input type="text" id="name" name="name" value="<?= $roomReserv['name'] ?>" /></div>
        <div>Start:</div>
        <div><input type="text" id="start" name="start" value="<?= $start ?>" /></div>
        <div>End:</div>
        <div><input type="text" id="end" name="end" value="<?= $end ?>" /></div>
        <div>Room:</div>
        <div>
            <select id="room" name="room">
                <?php
                foreach ($rooms as $room) {
                    $selected = $roomReserv['room_id'] == $room['id'] ? ' selected="selected"' : '';
                    $id = $room['id'];
                    $name = $room['name'];
                    print "<option value='$id' $selected>$name</option>";
                }
                ?>
            </select>

        </div>
        <div class="space"><input type="submit" value="Save" /> <a href="javascript:close();">Cancel</a></div>
    </form>

    <script>
        function close(result) {
            if (parent && parent.DayPilot && parent.DayPilot.ModalStatic) {
                parent.DayPilot.ModalStatic.close(result);
            }
        }

        $("#f").submit(function() {
            var f = $("#f");
            $.post(f.attr("action"), f.serialize(), function(result) {
                close(eval(result));
            });
            return false;
        });

        $(document).ready(function() {
            $("#name").focus();
        });
    </script>
</body>

</html>