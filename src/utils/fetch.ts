/**
 * Fetch
 */

import { buildQueryString } from "@wordpress/url";
import { ContextValues } from "../types";
import { Model as ContentModel } from "../types/content";

const {
    config: {endpoint},
    rest_bases: {post_type: postType},
} = window.Eckode

/**
 * WordPress API Fetch
 * 
 * Simple fetch abstraction to easily pass mandatory params.
 * 
 * @param {string} route Route to make request to.
 * @param {{}} options Options to attach to fetch request.
 * @param {{}} queryArgs Query parameters to bind to 'path' when making requests.
 * 
 * @returns {Promise<Array<ContentModel> | never>}
 */
export async function apiFetch(
  route: ContextValues,
  options: RequestInit = {},
  queryArgs: Record<string, string | number> = {},
): Promise<Array<ContentModel> | never> {
  const path = `${endpoint}/${postType[route]}/?${buildQueryString({
    ecko: 1,
    ...queryArgs,
  })}`;

  try {
    const request = await fetch(path, options);
    return await request.json();
  } catch (err) {
    if (err instanceof Error) {
      throw new Error(err.message as string);
    }
    throw new Error(err as string);
  }
}

export default {
  apiFetch,
};
