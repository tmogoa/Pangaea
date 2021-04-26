let currentTab = "drafts";

$("#drafts-btn").click(function () {
    if (currentTab != "drafts") {
        currentTab = "drafts";
        $("#published").toggle("hidden");
        $("#drafts").toggle("hidden");
    }
});

$("#published-btn").click(function () {
    if (currentTab != "published") {
        currentTab = "published";
        $("#published").toggle("hidden");
        $("#drafts").toggle("hidden");
    }
});
