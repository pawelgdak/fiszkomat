let lang = $("#fiszki")
    .find('input[name="lang"]')
    .val();
let lang_long = $("#fiszki")
    .find('input[name="lang_long"]')
    .val();
let i = 1;

let categories = [];

let extend = { 1: false };

let updateIndexedDB = false;

let flashcards = {
    old: [],
    new: []
};

if (typeof lang == "undefined") lang = "en";

// state manager

let db = new Dexie("state_manager");
db.version(1).stores({
    current: "setting, controller, action, href, flashcards"
});

function getUrlParameter(href, name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
    var results = regex.exec(href);
    return results === null
        ? ""
        : decodeURIComponent(results[1].replace(/\+/g, " "));
}

db.current.get("primary").then(data => {
    if (
        typeof data != "undefined" &&
        data.controller == "fiszki" &&
        data.action == "edit" &&
        typeof data.flashcards != undefined &&
        typeof data.flashcards.cat_id != undefined &&
        data.flashcards.cat_id == getUrlParameter(window.location.href, "id")
    ) {
        data.flashcards.old.forEach(el => {
            $("input[name='wordid[]'][value='" + el.id + "']")
                .parent()
                .find(".input-pl-edit")
                .val(el.pl);

            $("input[name='wordid[]'][value='" + el.id + "']")
                .parent()
                .find("textarea[name='noteedit[]']")
                .val(el.notes);

            $("input[name='wordid[]'][value='" + el.id + "']")
                .parent()
                .parent()
                .find(".input-foreign-edit")
                .val(el.fr);
        });

        for (let j = 1; j < data.flashcards.new.length; j++) {
            extend[j] = true;
            i++;
        }

        extend[data.flashcards.new.length] = false;

        $.post(
            "functions/Fiszki.php",
            {
                method: "dodajInput",
                id: 2,
                lang_long: lang_long,
                count: data.flashcards.new.length
            },
            function(x) {
                $("#fiszki").append(x);

                data.flashcards.new.forEach(el => {
                    $("input[name='pl[]'][data-id='" + el.id + "']").val(el.pl);
                    $("input[name='pl[]'][data-id='" + el.id + "']")
                        .parent()
                        .find("textarea[name='note[]']")
                        .val(el.notes);
                    $("input[name='pl[]'][data-id='" + el.id + "']")
                        .parent()
                        .parent()
                        .find("input[name='fo[]']")
                        .val(el.fr);
                });

                $(
                    "input[name='pl[]'][data-id='" +
                        parseInt(data.flashcards.new.length) +
                        "']"
                ).focus();
            }
        );
    } else {
        db.current.update("primary", { flashcards: {} }).then(() => {
            updateIndexedDB = true;
        });
    }
});

setInterval(() => {
    flashcards = {
        old: [],
        new: [],
        cat_id: $("input[name='cat_id']").val()
    };
    $(".input-pl-edit").each(function() {
        let id = $(this)
            .parent()
            .find("input[name='wordid[]']")
            .val();
        let notes = $(this)
            .parent()
            .find("textarea[name='noteedit[]']")
            .val();
        let pl = $(this).val();
        let fr = $(this)
            .parent()
            .parent()
            .find(".input-foreign-edit")
            .val();

        flashcards.old.push({ id, pl, fr, notes });
    });

    $(".input-pl").each(function() {
        let id = $(this).attr("data-id");
        let notes = $(this)
            .parent()
            .find("textarea[name='note[]']")
            .val();
        let pl = $(this).val();
        let fr = $(this)
            .parent()
            .parent()
            .find(".input-foreign")
            .val();

        flashcards.new.push({ id, pl, fr, notes });
    });

    db.current.get("primary").then(data => {
        if (
            typeof data != "undefined" &&
            data.controller == "fiszki" &&
            data.action == "edit"
        ) {
            db.current.update("primary", {
                flashcards
            });
        }
    });
}, 2000);

$(".fiszki-cat").on("click", function() {
    let catid = $(this)
        .find(".category-name")
        .attr("cat-id");

    if (categories.indexOf(catid) == -1) {
        $(this).css("background", "#e9ecef");
        categories.push(catid);
    } else {
        $(this).css("background", "#fff");
        index = categories.indexOf(catid);
        categories.splice(index, 1);
    }
});

$("#fiszki").on("blur", ".input-pl", function() {
    let setting = $("#at-button");

    let value = $(this).val();
    let id = $(this).attr("data-id");

    if (value != "") {
        if (extend[id] == false) {
            extend[i] = true;
            nowaFiszka();
        }

        if (
            typeof setting !== "undefined" &&
            setting.attr("data-setting") == "on"
        ) {
            $.post(
                "functions/translate.php",
                { lang: lang, value: value },
                function(x) {
                    let data = JSON.parse(x);

                    if (data.error == "false") {
                        $('.input-foreign[data-id="' + id + '"]').val(
                            data.translation
                        );
                    }
                }
            );
        }
    }
});

$("#fiszki").on("blur", ".input-pl-edit", function() {
    let setting = $("#at-button");

    if (
        typeof setting !== "undefined" &&
        setting.attr("data-setting") == "on"
    ) {
        let value = $(this).val();
        let id = $(this).attr("data-edit-id");

        if (value != "") {
            $.post(
                "functions/translate.php",
                { lang: lang, value: value },
                function(x) {
                    let data = JSON.parse(x);

                    if (data.error == "false") {
                        $('.input-foreign-edit[data-edit-id="' + id + '"]').val(
                            data.translation
                        );
                    }
                }
            );
        }
    }
});

function nowaFiszka() {
    i++;
    extend[i] = false;

    $.post(
        "functions/Fiszki.php",
        { method: "dodajInput", id: i, lang_long: lang_long },
        function(x) {
            $("#fiszki").append(x);
        }
    );
}

$("#fiszki").on("click", ".add-note", function() {
    let notesId = $(this).attr("data-id");
    $('textarea[data-id="' + notesId + '"]').css("display", "block");
});

$("#fiszki").on("click", ".edit-note", function() {
    let notesId = $(this).attr("data-edit-id");
    $('textarea[data-edit-id="' + notesId + '"]').css("display", "block");
});

$("#saveFlashcards").click(function() {
    $("#fiszki").submit();
});

$("#fiszki").on("click", ".remove-flashcard", function() {
    let fiszkaId = $(this).attr("data-edit-id");
    let elId = $(this).attr("data-obj-id");

    if (confirm("Jesteś pewny?")) {
        $.post(
            "functions/Fiszki.php",
            { method: "usunfiszke", id: fiszkaId, lang: lang },
            function(x) {
                $('li[data-id="' + elId + '"]').remove();
            }
        );
    }
});

$(".removeCategory").on("click", function(e) {
    let link = $(this).attr("href");
    e.preventDefault();

    if (confirm("Jesteś pewny?")) {
        document.location = link;
    }
});

$("#at-button").on("click", function() {
    let setting = $(this).attr("data-setting");
    let setTo;

    if (setting == "on") {
        $(this).attr("data-setting", "off");
        $(this).removeClass("at-on");
        $(this).addClass("at-off");
        $(this)
            .find(".at-text")
            .text("WYŁ");

        setTo = "false";
    } else if (setting == "off") {
        $(this).attr("data-setting", "on");
        $(this).removeClass("at-off");
        $(this).addClass("at-on");
        $(this)
            .find(".at-text")
            .text("WŁ");

        setTo = "true";
    }

    $.post(
        "functions/Fiszki.php",
        { method: "autoTranslate", setting: setTo },
        function(x) {}
    );
});

$(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

$(".changeCatSelect").select2();

$(".study-selected-button").on("click", function(e) {
    e.preventDefault();
    $(this).attr("href", $(this).attr("href") + "&id=");
    let that = $(this);

    categories.forEach(function(cat) {
        that.attr("href", that.attr("href") + cat + ",");
    });

    window.location = $(this).attr("href");
});

$(".change-cat").on("click", function() {
    let wordId = $(this).attr("data-change-id");
    let that = $(this);

    $("#changeCatModal").modal("toggle");

    $(".changeCatSelect").on("change", function() {
        let catId = $(this).val();

        $.post(
            "functions/Fiszki.php",
            { method: "zmienKat", id: wordId, cat: catId },
            function(x) {
                let data = JSON.parse(x);
                if (data.result) {
                    that.parent()
                        .parent()
                        .parent()
                        .remove();
                    $(".changeCatSelect")
                        .val("def")
                        .change();
                    $("#changeCatModal").modal("toggle");
                }
            }
        );
    });
});
