import {createBrowserRouter} from 'react-router-dom';
import Users from "./views/Users";
import Front from "./views/Front";
import NotFound from "./views/NotFound";

const router = createBrowserRouter([
  {
    path: '/',
    element: <Front/>
  },
  {
    path: '/users',
    element: <Users/>
  },
  {
    path: '*',
    element: <NotFound/>
  }
])

export default router;
