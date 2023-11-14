import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";
import React from "react";
import { render } from "react-dom";

const app = document.getElementById("app");
console.log("APp", app)
// render(
//     <createInertiaApp
//         initialPage={JSON.parse(app.dataset.page)}
//         resolveComponent={(name) => require(`./Pages/${name}/index.js`).default}
//     />,
//     app
// );

createInertiaApp({
    initialPage: JSON.parse(app.dataset.page),
    resolveComponent: (name) => require(`./Pages/${name}/index.js`).default,
  });