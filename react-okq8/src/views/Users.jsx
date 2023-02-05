import axiosClient from "../axios-client.jsx";
import {useEffect, useState} from "react";

export default function Users() {
  const [users, getUsers] = useState('');

  axiosClient.get("/users")
    .then(({data}) => {
      const users = data.users;
      getUsers(users);
      console.log(data.users);
    })
    .catch(err => {
      const response = err.response;
      console.log(response.data.errors);
    })

  return (
    <div>
      <h1>Users</h1>
      {users &&
        <ul>
          {Object.keys(users).map(key => (
            <li>#{users[key]['id']} <strong>{users[key]['name']}</strong></li>
          ))}
        </ul>
      }
    </div>
  )
}
