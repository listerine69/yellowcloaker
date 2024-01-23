<?php
require_once __DIR__ . '/initialization.php';
$menuQueryString = "config={$config}{$date_str}";
?>
<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="index.php?<?= $menuQueryString ?>">
                <img class="main-logo" src="img/logo.png" alt="" />
            </a>
            <strong>
                <img src="img/favicon.png" alt="" style="width:50px" />
            </strong>
        </div>
        <div class="nalika-profile">
            <div class="profile-dtl">
                <?php include 'version.php' ?>
            </div>
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">
                    <li>
                        <a title="Statistics" href="./statistics.php?<?= $menuQueryString ?>"
                           class="<?=str_contains($_SERVER['REQUEST_URI'],"statistics.php")?"metisselected":""?>">
                            <i class="bi bi-bar-chart-fill"></i>
                            <span class="mini-click-non">Statistics</span>
                        </a>
                    </li>
                    <li>
                        <a title="Leads" href="./index.php?filter=leads&<?= $menuQueryString ?>"
                           class="<?=str_contains($_SERVER['REQUEST_URI'],"index.php?filter=leads")?"metisselected":""?>">
                            <i class="bi bi-tag-fill"></i>
                            <span class="mini-click-non">Leads</span>
                        </a>
                    </li>
                    <li>
                        <a title="Allowed" href="./index.php?filter=allowed&<?= $menuQueryString ?>"
                           class="<?=str_contains($_SERVER['REQUEST_URI'],"index.php?filter=allowed")?"metisselected":""?>">
                            <i class="bi bi-emoji-smile-fill"></i>
                            <span class="mini-click-non">Allowed</span>
                        </a>
                    </li>
                    <li>
                        <a title="Blocked" href="./index.php?filter=blocked&<?= $menuQueryString ?>"
                           class="<?=str_contains($_SERVER['REQUEST_URI'],"index.php?filter=blocked")?"metisselected":""?>">
                            <i class="bi bi-ban"></i>
                            <span class="mini-click-non">Blocked</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="./editsettings.php?<?= $menuQueryString ?>" class="<?=str_contains($_SERVER['REQUEST_URI'],"editsettings.php")?"metisselected":""?>" aria-expanded="false">
                            <i class="bi bi-gear"></i>
                            <span class="mini-click-non">Configuration</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li>
                                <a id="addconfig" title="Add config">
                                    <i class="bi bi-patch-plus"></i>
                                    <span class="mini-sub-pro">Add Config</span>
                                </a>
                            </li>
                            <li>
                                <a id="dupconfig" title="Duplicate config">
                                    <i class="bi bi-patch-plus-fill"></i>
                                    <span class="mini-sub-pro">Duplicate Config</span>
                                </a>
                            </li>
                            <li>
                                <a id="delconfig" title="Delete config">
                                    <i class="bi bi-patch-minus"></i>
                                    <span class="mini-sub-pro">Delete Config</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </nav>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function reloadWithConfig(configName) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('config', configName);
            const newUrl = `${window.location.origin}${window.location.pathname}?${urlParams.toString()}`;
            window.location.href = newUrl;
        }

        document.getElementById("saveconfig")?.addEventListener("submit", async (e) => {
            e.preventDefault();

            let res = await fetch("configmanager.php?action=save&name=<?= $config ?>", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(new FormData(document.getElementById("saveconfig")).entries()).toString()
            });
            let js = await res.json();
            if (js["result"] === "OK")
                alert("Settings saved!")
            else
                alert(`An error occured: ${js["result"]}`);
            return false;
        });

        document.getElementById("addconfig").onclick = async () => {
            let configName = prompt("Enter new config name:");
            if (configName === '' || configName == null) return;
            let res = await fetch("configmanager.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add&name=${configName}`
            });
            let js = await res.json();
            if (js["result"] === "OK")
                reloadWithConfig(configName);
            else
                alert(`An error occured: ${js["result"]}`);
        };

        document.getElementById("dupconfig").onclick = async () => {
            let curConfigName = "<?= $config ?>";
            let newConfigName = prompt("Enter new config name:");
            if (newConfigName === '' || newConfigName == null) return;
            let res = await fetch("configmanager.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=dup&name=${curConfigName}&dupname=${newConfigName}`
            });
            let js = await res.json();
            if (js["result"] === "OK")
                reloadWithConfig(newConfigName);
            else
                alert(`An error occured: ${js["result"]}`);
        };

        document.getElementById("delconfig").onclick = async () => {
            let config = "<?= $config ?>";
            if (config === 'default') {
                alert("Can't delete default config!");
                return;
            }
            if (!confirm(`Are you sure you want to delete this config: ${config}?`)) return;
            let res = await fetch("configmanager.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=del&name=${config}`
            });
            let js = await res.json();
            if (js["result"] === "OK")
                reloadWithConfig("default");
            else
                alert(`An error occured: ${js["result"]}`);
        };
    });
</script>
