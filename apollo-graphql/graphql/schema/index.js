const {gql} = require("apollo-server");

const typeDefs = gql`
    type User {
        id: ID!
        name: String!
        age: Int!
        courses: [Course!]!
        department: Department
    }

    type Course {
        id: ID!
        name: String!
        users: [User]!
    }

    type Department {
        name: String!
    }

    type Query {
        user(id: ID!): User!
        users: [User!]!
        course(id: ID!): Course!
        courses: [Course!]!
    }

    input CreateUserInput {
        name: String!
        age: Int!
    }
    
    type Mutation {
        createUser(input: CreateUserInput!): User!
    }
`;

module.exports = {typeDefs};