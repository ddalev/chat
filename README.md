# OpenAI Chat
## Example with OpenAI implementation

### Initial Setup

#### Install Lando
- [Get Lando for Linux](https://docs.lando.dev/install/linux.html)
- [Get Lando for MacOs](https://docs.lando.dev/install/macos.html)
- lando start

#### Add OpenAi api keys into .env
- OPENAI_API_KEY=
- OPENAI_ORGANIZATION=

- lando restart

#### Access the functionality form
- https://chat.lndo.site/chat

#### Chat and source properties
- config/chat.php
- - system instructions
- - Wikipedia source page title
