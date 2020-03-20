# Entities

entity = data
repository = entities
decorator = variables
provider = repositories && decorators

base = no abstract functions
abstract = abstract functions

filters

Routes
    resource: '../../src/Controller/'
    prefix: '/blog'
    # name_prefix: ''
    trailing_slash_on_root: false

blog_list:
    path:       /blog/{page<\d+>?1}
    controller: App\Controller\BlogController::list

```
phpbb
├─ entity
│   ├─ exception
│   │   ├─ entity_exists_exception
│   │   ├─ entity_missing_exception
│   │   └─ entity_not_found_exception
│   ├─ base
│   ├─ base_interface
│   ├─ manager
│   └─ manager_interface
├─ forum
│   ├─ controller
│   │   ├─ forum
│   │   └─ forum_interface
│   ├─ entity
│   │   ├─ forum
│   │   └─ forum_interface
│   └─ manager
│       ├─ forum
│       └─ forum_interface
├─ post
│   ├─ controller
│   │   ├─ post
│   │   └─ post_interface
│   ├─ entity
│   │   ├─ post
│   │   └─ post_interface
│   └─ manager
│       ├─ post
│       └─ post_interface
├─ topic
│   ├─ controller
│   │   ├─ topic
│   │   └─ topic_interface
│   ├─ entity
│   │   ├─ topic
│   │   └─ topic_interface
│   └─ manager
│       ├─ topic
│       └─ topic_interface
└─ user
    ├─ controller
    │   ├─ user
    │   └─ user_interface
    ├─ entity
    │   ├─ user
    │   └─ user_interface
    └─ manager
        ├─ user
        └─ user_interface
```


