// // vite.config.js
// import { defineConfig } from "file:///C:/laragon/www/inertia-app3/node_modules/vite/dist/node/index.js";
// import laravel from "file:///C:/laragon/www/inertia-app3/node_modules/laravel-vite-plugin/dist/index.js";
// import vue from "file:///C:/laragon/www/inertia-app3/node_modules/@vitejs/plugin-vue/dist/index.mjs";
// import AutoImport from "file:///C:/laragon/www/inertia-app3/node_modules/unplugin-auto-import/dist/vite.js";
// import Components from "file:///C:/laragon/www/inertia-app3/node_modules/unplugin-vue-components/dist/vite.js";
// import { ElementPlusResolver } from "file:///C:/laragon/www/inertia-app3/node_modules/unplugin-vue-components/dist/resolvers.js";
// import { visualizer } from "file:///C:/laragon/www/inertia-app3/node_modules/rollup-plugin-visualizer/dist/plugin/index.js";
// var vite_config_default = defineConfig({
//   plugins: [
//     laravel({
//       input: "resources/js/app.js",
//       refresh: true
//     }),
//     vue({
//       template: {
//         transformAssetUrls: {
//           base: null,
//           includeAbsolute: false
//         }
//       }
//     }),
//     AutoImport({
//       resolvers: [ElementPlusResolver()]
//     }),
//     Components({
//       resolvers: [ElementPlusResolver()]
//     }),
//     visualizer({
//       open: true,
//       // Automatically open the report in your browser
//       filename: "bundle-analysis.html"
//       // Specify the output file name
//     })
//   ]
// });
// export {
//   vite_config_default as default
// };
// //# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFxpbmVydGlhLWFwcDNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXGxhcmFnb25cXFxcd3d3XFxcXGluZXJ0aWEtYXBwM1xcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovbGFyYWdvbi93d3cvaW5lcnRpYS1hcHAzL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCBBdXRvSW1wb3J0IGZyb20gJ3VucGx1Z2luLWF1dG8taW1wb3J0L3ZpdGUnXG5pbXBvcnQgQ29tcG9uZW50cyBmcm9tICd1bnBsdWdpbi12dWUtY29tcG9uZW50cy92aXRlJ1xuaW1wb3J0IHsgRWxlbWVudFBsdXNSZXNvbHZlciB9IGZyb20gJ3VucGx1Z2luLXZ1ZS1jb21wb25lbnRzL3Jlc29sdmVycydcbmltcG9ydCB7IHZpc3VhbGl6ZXIgfSBmcm9tICdyb2xsdXAtcGx1Z2luLXZpc3VhbGl6ZXInXG5cblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6ICdyZXNvdXJjZXMvanMvYXBwLmpzJyxcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXG4gICAgICAgIH0pLFxuICAgICAgICB2dWUoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IHtcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm1Bc3NldFVybHM6IHtcbiAgICAgICAgICAgICAgICAgICAgYmFzZTogbnVsbCxcbiAgICAgICAgICAgICAgICAgICAgaW5jbHVkZUFic29sdXRlOiBmYWxzZSxcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgfSxcbiAgICAgICAgfSksXG4gICAgICAgIEF1dG9JbXBvcnQoe1xuICAgICAgICAgICAgcmVzb2x2ZXJzOiBbRWxlbWVudFBsdXNSZXNvbHZlcigpXSxcbiAgICAgICAgICB9KSxcbiAgICAgICAgQ29tcG9uZW50cyh7XG4gICAgICAgIHJlc29sdmVyczogW0VsZW1lbnRQbHVzUmVzb2x2ZXIoKV0sXG4gICAgICAgIH0pLFxuICAgICAgICB2aXN1YWxpemVyKHtcbiAgICAgICAgICAgIG9wZW46IHRydWUsIC8vIEF1dG9tYXRpY2FsbHkgb3BlbiB0aGUgcmVwb3J0IGluIHlvdXIgYnJvd3NlclxuICAgICAgICAgICAgZmlsZW5hbWU6ICdidW5kbGUtYW5hbHlzaXMuaHRtbCcsIC8vIFNwZWNpZnkgdGhlIG91dHB1dCBmaWxlIG5hbWVcbiAgICAgICAgfSksXG4gICAgXSxcbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUEyUSxTQUFTLG9CQUFvQjtBQUN4UyxPQUFPLGFBQWE7QUFDcEIsT0FBTyxTQUFTO0FBQ2hCLE9BQU8sZ0JBQWdCO0FBQ3ZCLE9BQU8sZ0JBQWdCO0FBQ3ZCLFNBQVMsMkJBQTJCO0FBQ3BDLFNBQVMsa0JBQWtCO0FBRzNCLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLFNBQVM7QUFBQSxJQUNMLFFBQVE7QUFBQSxNQUNKLE9BQU87QUFBQSxNQUNQLFNBQVM7QUFBQSxJQUNiLENBQUM7QUFBQSxJQUNELElBQUk7QUFBQSxNQUNBLFVBQVU7QUFBQSxRQUNOLG9CQUFvQjtBQUFBLFVBQ2hCLE1BQU07QUFBQSxVQUNOLGlCQUFpQjtBQUFBLFFBQ3JCO0FBQUEsTUFDSjtBQUFBLElBQ0osQ0FBQztBQUFBLElBQ0QsV0FBVztBQUFBLE1BQ1AsV0FBVyxDQUFDLG9CQUFvQixDQUFDO0FBQUEsSUFDbkMsQ0FBQztBQUFBLElBQ0gsV0FBVztBQUFBLE1BQ1gsV0FBVyxDQUFDLG9CQUFvQixDQUFDO0FBQUEsSUFDakMsQ0FBQztBQUFBLElBQ0QsV0FBVztBQUFBLE1BQ1AsTUFBTTtBQUFBO0FBQUEsTUFDTixVQUFVO0FBQUE7QUFBQSxJQUNkLENBQUM7QUFBQSxFQUNMO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
