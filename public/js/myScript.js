var settings = {
    url: "http://localhost:8000/api/users",
    method: "GET",
    headers: {
        Accept: "application/json",
    },
};

$.ajax(settings).done(function (response) {
    console.log(response);
});
