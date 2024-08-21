$(document).ready(function () {
    var infobarDefault = $("#infobar").html();

    $("#transaction-account-type").change(function (e) {
        unlock();
        loadAccounts();
    });

    $("#transaction-payment-method").change(function (e) {
        if ($("#transaction-account-type").val() == "cheque") return;

        var payByCheque = $(this).val() == "cheque";
        $("#transaction-cheque-no").prop("disabled", !payByCheque);
        $("#transaction-cheque-due-date").prop("disabled", !payByCheque);
        $("#transaction-description").prop("disabled", payByCheque);
    });

    $("#transaction-account-name").change(function (e) {
        var me = $(this);
        var id = "#" + $(this).attr("list");

        var option = $(id)
            .find("option")
            .filter(function () {
                return $(this).val() == me.val();
            });
        $("#transaction-account-id").val(option.attr("data-id"));
    });

    $("#transaction-cheque-no").change(function (e) {
        var me = $(this);
        if ($("#transaction-account-type").val() == "cheque") {
            var url = $(this)
                .attr("data-cheque-url")
                .replace("#", $(this).val());
            console.log(url);

            $.get(url, [], function (data) {
                if (data.due_amount == 0) {
                    alert("এই চেকের টাকা পরিশোধ হয়েছে।");
                    $("#infobar").parent().addClass("d-none");
                    me.val("");
                    me.focus();
                    return;
                }
                $("#infobar").html(
                    data.name + " (" + data.id + "): বাকী = " + data.due_amount
                );
                $("#infobar").parent().removeClass("d-none");
            }).fail(function () {
                alert("এই চেক এখনও ইস্যু করা হয় নি।");
                $("#infobar").parent().addClass("d-none");
                me.val("");
                me.focus();
            });
        }
    });

    $(document).ready(function () {
        $("#transaction-amount").change(function (e) {
            var type = $("#transaction-account-type").val();
            if (type == "employee") {
                var id = $("#transaction-account-id").val();
                var url = employeeLimitUrl;
                var amount = $(this).val();
                var postData = {
                    id: id,
                    amount: amount,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                };
                $.ajax({
                    url: url,
                    method: "POST",
                    data: postData,
                    success: function (data) {
                        if (data.data.status == 0) {
                            alert(
                                "এই স্টাফ এর টাকা তোলার লিমিট " +
                                    data.data.limit
                            );
                            $("#transaction-amount").val("");
                            $('.locked').attr(disabled);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error:", error);
                    },
                });
            }
        });
    });

    $("#transaction-cheque-no").change(function (e) {
        var me = $(this);
        if ($("#transaction-account-type").val() == "cheque") {
            var url = $(this)
                .attr("data-cheque-url")
                .replace("#", $(this).val());
            $.get(url, [], function (data) {
                if (data.due_amount == 0) {
                    alert("এই চেকের টাকা পরিশোধ হয়েছে।");
                    $("#infobar").parent().addClass("d-none");
                    me.val("");
                    me.focus();
                    return;
                }
                $("#infobar").html(
                    data.name + " (" + data.id + "): বাকী = " + data.due_amount
                );
                $("#infobar").parent().removeClass("d-none");
            }).fail(function () {
                alert("এই চেক এখনও ইস্যু করা হয় নি।");
                $("#infobar").parent().addClass("d-none");
                me.val("");
                me.focus();
            });
        }
    });
});

function unlock() {
    var accountType = $("#transaction-account-type");
    var paymentType = $("#transaction-payment-type");
    var hiddenIncome = $("#transaction-hidden-income");
    var hiddenExpense = $("#transaction-hidden-expense");
    var paymentMethod = $("#transaction-payment-method");
    var cashAccount = $("#transaction-payment-method option").first();
    var cheque = $("#transaction-payment-method option").last();
    var accountName = $("#transaction-account-name");
    var accountId = $("#transaction-account-id");
    var chequeNo = $("#transaction-cheque-no");
    var chequeDueDate = $("#transaction-cheque-due-date");
    var firstBank = cashAccount.next();

    $(".locked").prop("disabled", accountType.val() == "");
    $("#infobar").parent().addClass("d-none");

    accountName.val("");
    accountId.val("");

    switch (accountType.val()) {
        case "withdraw":
        case "deposit":
            cashAccount.prop("disabled", true);
            cheque.prop("disabled", true);
            paymentType.prop("disabled", true);
            hiddenIncome.prop("disabled", true);
            hiddenExpense.prop("disabled", true);
            accountName.prop("disabled", true);
            accountId.prop("disabled", true);
            chequeNo.prop("disabled", true);
            chequeDueDate.prop("disabled", true);

            paymentType.val(
                accountType.val() == "withdraw" ? "income" : "expense"
            );
            paymentMethod.val(firstBank.val());
            break;

        case "factory":
        case "gift-supplier":
            cashAccount.prop("disabled", false);
            cheque.prop("disabled", false);
            paymentType.prop("disabled", true);
            hiddenIncome.prop("disabled", true);
            chequeNo.prop("disabled", true);
            chequeDueDate.prop("disabled", true);

            paymentType.val("expense");
            paymentMethod.val(cashAccount.val());
            break;

        case "retail-store":
        case "retail-closing":
            cashAccount.prop("disabled", false);
            cheque.prop("disabled", true);
            paymentType.prop("disabled", true);
            hiddenExpense.prop("disabled", true);
            chequeNo.prop("disabled", true);
            chequeDueDate.prop("disabled", true);

            paymentType.val("income");
            paymentMethod.val(cashAccount.val());
            break;

        case "cheque":
            cashAccount.prop("disabled", false);
            cheque.prop("disabled", true);
            paymentType.prop("disabled", true);
            hiddenIncome.prop("disabled", true);
            accountName.prop("disabled", true);
            accountId.prop("disabled", true);
            chequeNo.prop("disabled", false);
            chequeDueDate.prop("disabled", true);

            paymentType.val("expense");
            paymentMethod.val(cashAccount.val());
            break;

        case "employee":
        case "expense":
            cashAccount.prop("disabled", false);
            cheque.prop("disabled", true);
            paymentType.prop("disabled", true);
            hiddenIncome.prop("disabled", true);
            chequeNo.prop("disabled", true);
            chequeDueDate.prop("disabled", true);
            paymentType.val("expense");
            paymentMethod.val(cashAccount.val());
            break;

        case "loan-receipt":
            cashAccount.prop("disabled", false);
            cheque.prop("disabled", true);
            hiddenIncome.prop("disabled", true);
            paymentType.prop("disabled", true);
            chequeNo.prop("disabled", true);
            chequeDueDate.prop("disabled", true);
            paymentType.val("income");
            paymentMethod.val(cashAccount.val());
            break;
        case "loan-payment":
            cashAccount.prop("disabled", false);
            cheque.prop("disabled", true);
            hiddenIncome.prop("disabled", true);
            paymentType.prop("disabled", true);
            chequeNo.prop("disabled", true);
            chequeDueDate.prop("disabled", true);
            paymentType.val("expense");
            paymentMethod.val(cashAccount.val());
            break;

    }
}

function loadAccounts() {
    var account = $("#transaction-account-type").val();

    if (account == "withdraw" || account == "deposit" || account == "cheque") {
        return;
    }
    var datalistId = "#" + account + "-list";

    if ($(datalistId).length == 0) {
        var datalistUrl = $("#transaction-account-type")
            .attr("data-datalist-url")
            .replace("factory", account);

        $.get(datalistUrl, [], function (data) {
            $("#site-content").append(data);
            console.log(datalistUrl);
            $("#transaction-account-name").attr("list", account + "-list");
        });
    } else {
        $("#transaction-account-name").attr("list", account + "-list");
    }
}
