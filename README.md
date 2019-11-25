# DigiBook

DigiBook is an information security challenge in the Web category, and was presented to participants of [KAF CTF 2019](https://ctf.kipodafterfree.com)

## Challenge story

We let an AI write a book, read it here! (good for binge reading with friends) #2019Clickbait

## Challenge exploit

Session ID & IP abuse

## Challenge solution

No need

## Building and installing

[Clone](https://github.com/NadavTasher/2019-DigiBook/archive/master.zip) the repository, then type the following command to build the container:
```bash
docker build . -t digibook
```

To run the challenge, execute the following command:
```bash
docker run --rm -d -p 1030:80 digibook
```

## Usage

You may now access the challenge interface through your browser: `http://localhost:1030`

## Flag

Flag is:
```flagscript
KAF{D33z_b00k_s0_1nt3r3s71n9}
```

## License
[MIT License](https://choosealicense.com/licenses/mit/)