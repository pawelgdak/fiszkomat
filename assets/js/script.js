$(function() {
    $.get("functions/getConf.php", function(data) {
        let path = data;

        /* Text to Speech */
        $(document).on("click", ".textToSpeech", function() {
            let text = $(this).attr("data-text");
            let lang = $(this).attr("data-lang");
            let voice;

            switch (lang) {
                case "en":
                    voice = "US English Male";
                    break;

                case "es":
                    voice = "Spanish Female";
                    break;

                case "de":
                    voice = "Deutsch Female";
                    break;

                case "fr":
                    voice = "French Female";
                    break;

                case "pl":
                    voice = "Polish Female";
                    break;

                case "ru":
                    voice = "Russian Female";
                    break;

                case "it":
                    voice = "Italian Female";
                    break;

                default:
                    voice = "UK English Male";
            }

            responsiveVoice.speak(text, voice);
        });

        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var baseUrl = "assets/images/flags";
            var $state = $(
                '<div style="display:flex; align-items: center;"><img src="' +
                    baseUrl +
                    "/" +
                    state.element.value.toLowerCase() +
                    '.png" class="img-flag" /> <span style="padding-left: 10px">' +
                    state.text +
                    "</span></div>"
            );
            return $state;
        }

        $("#pick-language").select2({
            minimumResultsForSearch: Infinity,
            placeholder: "Jakiego języka chcesz się uczyć?",
            width: "240px",
            templateResult: formatState
        });

        $("#pick-language").on("select2:select", function(e) {
            var SelectData = e.params.data;

            $.post(
                "functions/setLanguage.php",
                { data: JSON.stringify(SelectData) },
                function(x) {
                    window.location = path + "?c=fiszki";
                }
            );
        });

        let clicked = false;
        let oldName = "";
        let inp = "";
        let inputType = "";

        $(".edit-element").on("click", ".edit-element-child", function() {
            oldName = $(this).text();

            if (!clicked) {
                clicked = true;
                inputType = $(this)
                    .parent()
                    .attr("data-input");

                var t = $(this)
                    .text()
                    .trim();

                if (inputType == "text") {
                    $(this)
                        .text("")
                        .append($("<input />", { value: t }));
                    $(this)
                        .find("input")
                        .addClass("input-edit");
                } else if (inputType == "textarea") {
                    $(this)
                        .text("")
                        .append($("<textarea />", { text: t }));
                    $(this)
                        .find("textarea")
                        .addClass("textarea-edit");
                }

                if (inputType == "text")
                    $(this)
                        .find("input")
                        .focus();
                else if (inputType == "textarea")
                    $(this)
                        .find("textarea")
                        .focus();
            }

            if (inputType == "text") inp = "input";
            else if (inputType == "textarea") inp = "textarea";

            $(this)
                .parent()
                .on("blur", inp, function() {
                    let id = $(this)
                        .parent()
                        .parent()
                        .attr("data-id");

                    let newName = $(this)
                        .val()
                        .trim();
                    let editType = $(this)
                        .parent()
                        .parent()
                        .attr("data-type");
                    let elementType = $(this)
                        .parent()
                        .parent()
                        .attr("data-element");
                    let that = $(this);
                    let minLen = $(this)
                        .parent()
                        .parent()
                        .attr("data-min-length");
                    let maxLen = $(this)
                        .parent()
                        .parent()
                        .attr("data-max-length");
                    let emptyText = $(this)
                        .parent()
                        .parent()
                        .attr("data-empty-text");

                    if (typeof minLen == "undefined") minLen = 0;
                    if (typeof maxLen == "undefined") maxLen = 0;
                    if (typeof emptyText == "undefined") emptyText = "";

                    clicked = false;

                    if (
                        (inputType == "text" &&
                            newName.length > minLen &&
                            newName.length < maxLen) ||
                        inputType == "textarea"
                    ) {
                        if (newName != emptyText) {
                            $.post(
                                "functions/changeElement.php",
                                {
                                    editType: editType,
                                    elementType: elementType,
                                    id: id,
                                    newName: newName
                                },
                                function(x) {
                                    let data = JSON.parse(x);

                                    if (data.result == "done") {
                                        if (that.val().length == 0)
                                            that.parent().text(emptyText);
                                        else that.parent().text(that.val());
                                    } else that.parent().text(oldName);
                                }
                            );
                        } else
                            $(this)
                                .parent()
                                .text(oldName);
                    } else
                        $("#edit-cat-name input")
                            .parent()
                            .text(oldName);
                });
        });
    });
});
