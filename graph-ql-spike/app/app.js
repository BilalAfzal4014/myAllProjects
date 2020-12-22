const express = require("express");
const expressGraphQl = require("express-graphql");
const {GraphQLSchema, GraphQLObjectType, GraphQLString, GraphQLList, GraphQLNonNull, GraphQLInt} = require("graphql");
const app = express();


const authors = [
    {id: 1, name: 'J. K. Rowling'},
    {id: 2, name: 'J. R. R. Tolkien'},
    {id: 3, name: 'Brent Weeks'}
];

const books = [
    {id: 1, name: 'Harry Potter and the Chamber of Secrets', authorId: 1},
    {id: 2, name: 'Harry Potter and the Prisoner of Azkaban', authorId: 1},
    {id: 3, name: 'Harry Potter and the Goblet of Fire', authorId: 1},
    {id: 4, name: 'The Fellowship of the Ring', authorId: 2},
    {id: 5, name: 'The Two Towers', authorId: 2},
    {id: 6, name: 'The Return of the King', authorId: 2},
    {id: 7, name: 'The Way of Shadows', authorId: 3},
    {id: 8, name: 'Beyond the Shadows', authorId: 3}
];


const BookType = new GraphQLObjectType({
    name: "BookType",
    description: "To get a book",
    fields: () => {
        console.log("i am at BookType fields");
        return {
            id: {
                type: GraphQLNonNull(GraphQLInt)
            },
            name: {
                type: GraphQLNonNull(GraphQLString)
            },
            authorID: {
                type: GraphQLNonNull(GraphQLInt),
                resolve: (book) => {
                    console.log("authorId", book) //will console for each book type
                    for (let author of authors) {
                        if (author.id === book.authorId) {
                            return author.id
                        }
                    }
                }
            },
            authorName: {
                type: GraphQLNonNull(GraphQLString),
                resolve: (book) => {
                    console.log("authorName", book) //will console for each book type
                    for (let author of authors) {
                        if (author.id === book.authorId) {
                            return author.name
                        }
                    }
                }
            },
            fullAuthor: {
                type: AuthorType,
                resolve: (book) => {
                    // what we will return here will pass to AuthorType
                    console.log("fullAuthor", book) //will console for each book type
                    for (let author of authors) {
                        if (author.id === book.authorId) {
                            return author
                        }
                    }
                }
            }
        }
    }
});


const AuthorType = new GraphQLObjectType({
    name: "AuthorType",
    description: "To get Author",
    fields: () => {
        return {
            id: {
                type: GraphQLNonNull(GraphQLInt)
            },
            name: {
                type: GraphQLNonNull(GraphQLString)
            },
            books: {
                type: new GraphQLList(BookType),
                resolve: (author) => {
                    let booksOfAuthor = [];
                    for (let book of books)
                        if (book.authorId === author.id)
                            booksOfAuthor.push(book);
                    console.log("AuthorType", author);
                    return booksOfAuthor;
                }
            }
        }
    }
});


const RootQueryType = new GraphQLObjectType({
    name: "query",
    description: "Root Query",
    fields: () => {
        console.log("i am root query fields")
        return {
            message: {
                type: GraphQLString,
                resolve: () => {
                    return "Hello World"
                }
            },
            list: {
                type: new GraphQLList(GraphQLString),
                description: 'Name of all books',
                resolve: () => {
                    let newBooks = [];
                    for (let book of books)
                        newBooks.push(book.name);
                    return newBooks
                }
            },
            books: {
                type: new GraphQLList(BookType),
                description: "All books",
                resolve: () => {
                    // what we will return here will pass to BookType, our type is of array so bookType will be invoke number of times of size of array
                    console.log("i am at books resolve"); //console once
                    return new Promise((resolve, reject) => {

                        setTimeout(() => {
                            resolve(books);
                        }, 3000);

                    });
                    //return books;
                }
            },
            book: {
                type: BookType,
                description: "Get a single book",
                args: {
                    id: {type: GraphQLInt}
                },
                resolve: (parent, args) => {
                    for (let book of books)
                        if (book.id === args.id)
                            return book;
                }
            },
            author: {
                type: AuthorType,
                description: "Get a single author",
                args: {
                    id: {type: GraphQLInt}
                },
                resolve: (parent, args) => {
                    for (let author of authors)
                        if (author.id === args.id)
                            return author;
                }
            },
            authors: {
                type: new GraphQLList(AuthorType),
                description: "Get list of authors",
                resolve: () => {
                    console.log("authors", authors)
                    return authors;
                }
            }
        }
    },
});


const RootMutationType = new GraphQLObjectType({
    name: "Mutation",
    description: "Root Mutation",
    fields: () => {
        return {
            addBook: {
                type: BookType,
                description: "Add a book",
                args: {
                    name: {
                        type: GraphQLNonNull(GraphQLString)
                    },
                    authorId: {
                        type: GraphQLNonNull(GraphQLInt)
                    }
                },
                resolve: (parent, args) => {

                    //throw new Error("USER_ALREADY_EXISTS");

                    throw new Error(JSON.stringify({
                        status: 404,
                        message: "Author Not Found"
                    }));

                    let newBook = {
                        id: books.length + 1,
                        name: args.name,
                        authorId: args.authorId,
                    }

                    books.push(newBook);
                    return newBook;
                }
            },
            addAuthor: {
                type: AuthorType,
                description: "Add an author",
                args: {
                    name: {
                        type: GraphQLNonNull(GraphQLString)
                    }
                },
                resolve: (parent, args) => {
                    let newAuthor = {
                        id: authors.length + 1,
                        name: args.name
                    };
                    authors.push(newAuthor);
                    return newAuthor;
                }
            }
        };
    }
});

const schema = new GraphQLSchema({
    query: RootQueryType,
    mutation: RootMutationType
})

app.use("/graphql", function(req, res, next){
    console.log("i am in graphql middleware, this middleware is just for the sake of testing")
    next();
},expressGraphQl.graphqlHTTP({
    schema: schema,
    graphiql: true,
    customFormatErrorFn: (err) => {
        let error;
        try {
            error = JSON.parse(err.message);
        } catch (e) {
            error = getErrorCode(err.message)
        }
        return {
            status: error.status,
            message: error.message
        };
    }
}));

module.exports = app;


function getErrorCode(errorName) {

    /*let errorName = {
        USER_ALREADY_EXISTS: 'USER_ALREADY_EXISTS',
        SERVER_ERROR: 'SERVER_ERROR'
    }*/

    let errorType = {
        USER_ALREADY_EXISTS: {
            message: 'User already exists.',
            status: 403
        },
        SERVER_ERROR: {
            message: 'Server error.',
            status: 500
        }
    };

    return errorType[errorName];

}