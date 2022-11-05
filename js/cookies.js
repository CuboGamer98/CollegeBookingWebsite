function getCookie(name, def, func) {
    $.ajax({
      type: 'POST',
      url: './includes/cookies.inc.php',
      data: "action=get&name=" + name + "&def=" + def,
      success: function(data, textStatus, jqXHR) {
        func(data);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
      }
    });
}

function setCookie(name, value) {
    $.ajax({
      type: 'POST',
      url: 'includes/cookies.inc.php',
      data: "action=set&name=" + name + "&value=" + value,
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
      }
    });
}