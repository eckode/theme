import React, { lazy } from "react";
import { Route, RouteObject, Routes } from "react-router-dom";

import Header from "./Header";
import Footer from "./Footer";
import Home from "../routes/Home";

// Utils
import { useRouteElement } from "../app/hooks";

interface RouteComponents {
  [key: string]: React.LazyExoticComponent<() => React.ReactElement>;
}

// Lazy components
const routeComponents: RouteComponents = {
  home: lazy(() => import("../routes/Home")),
  single: lazy(() => import("../routes/Single")),
  taxonomy: lazy(() => import("../routes/Taxonomy")),
  not_found: lazy(() => import("../routes/NotFound")),
  post_type_archive: lazy(() => import("../routes/Archive")),
};

// Layout
const Layout = (): JSX.Element => {
  const element = useRouteElement();
  const DynamicLazyComponent = routeComponents[element] ?? React.Fragment;
  console.log("LAYOUT:", element);
  return (
    <div className="eckode">
      <Header />
      <main>
        <Routes>
            <Route path="/" element={<Home />} />
            <Route
                path="*"
                element={
                  <React.Suspense fallback={<>Loading...</>}>
                    <DynamicLazyComponent />
                  </React.Suspense>
                }
              />
          </Routes>
      </main>
      <Footer />
    </div>
  );
};

export default Layout;
