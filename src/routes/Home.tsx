import React, { Suspense } from "react";
import { useLocation, useParams } from "react-router-dom";
import Content from "../components/content/Content";

const Home = (): JSX.Element => {
  return (
    <div>
      <h1>Home</h1>
      <Suspense fallback={<p>Loading...</p>}>
        <Content type="post" />
      </Suspense>
    </div>
  );
};

export default Home;
