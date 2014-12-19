$(function ()
{
    $("#grid").bootgrid({
        url: "/api/data/basic",
        formatters: {
            "filename": function(column, row)
            {
                return "<a href=\"#\">" + column.id + ": " + row.id + "</a>";
            }
        }
    });
});
