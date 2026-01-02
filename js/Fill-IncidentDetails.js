function fillIncidentDetails(incident) {
    if (!incident || typeof incident !== "object") return;

    const descriptionEl = document.getElementById("description");
    if (descriptionEl && incident.description !== null) {
        descriptionEl.value = incident.description;
    }

    const datetimeEl = document.getElementById("occurrence_datetime");
    if (datetimeEl && incident.occurrence_datetime) {
        datetimeEl.value = incident.occurrence_datetime.replace(" ", "T").slice(0, 16);
    }

    const severityEl = document.getElementById("severity");
    if (severityEl && incident.severity) {
        [...severityEl.options].forEach(option => {
            if (option.text === incident.severity) {
                option.selected = true;
            }
        });
    }

    const typeEl = document.getElementById("incident_type");
    if (typeEl && incident.incident_type) {
        [...typeEl.options].forEach(option => {
            if (option.text === incident.incident_type) {
                option.selected = true;
            }
        });
    }

    if (incident.assets) {
        const assetList = incident.assets.split(",").map(a => a.trim());

        assetList.forEach(assetName => {
            const labels = document.querySelectorAll("#asset_container label");

            labels.forEach(label => {
                if (label.textContent.trim() === assetName) {
                    const checkbox = document.getElementById(label.htmlFor);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                }
            });
        });
    }

    const statusEl = document.querySelector('input[name="status"]');
    if (statusEl && incident.status !== null) {
        statusEl.value = incident.status;
    }
}