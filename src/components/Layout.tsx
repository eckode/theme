import React, { lazy } from "react";
import {
  Route,
  Routes,
  // Link,
  // Outlet,
} from "react-router-dom";
import Content from "./content-type/ContentType";

import Header from "./Header";
import Home from "../routes/Home";

// Utils
import { useRouteElement } from "../app/hooks";
import { Contexts } from "../types";

interface RouteComponents {
  [key: string]:
    | React.LazyExoticComponent<() => React.ReactElement>;
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
  console.log(element);
  const DynamicLazyComponent = routeComponents[element] ?? React.Fragment;
  return (
    <>
      <Header />
      <Content />
      <main>
        <Routes>
          <Route path="/" element={<Home />} />
          {'' !== element && <Route
            path="*"
            element={
              <React.Suspense fallback={<>Loading...</>}>
                <DynamicLazyComponent />
              </React.Suspense>
            }
          />}
        </Routes>
      </main>
    </>
  );
};

export default Layout;
