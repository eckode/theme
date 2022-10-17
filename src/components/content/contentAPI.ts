import * as ContentType from "./types";

// A mock function to sss making an async request for data
export async function fetchContent(
  endpoint: string,
): Promise<[string, ContentType.AllObject]> {
  const content = await fetch(
    `https://jaredrethman.com/wp-json/wp/v2/${endpoint}?ecko=1`,
  );
  const response = await content.json();
  return [endpoint, response];
}

const API = {
  fetchContent,
};

export default API;
