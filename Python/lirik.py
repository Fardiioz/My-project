import time

def main():
    lirik = {
        "Ayat 1": [
            ("Doushite sugu shitte shimau no", 1.2),
            ("Kyoushin de kurushinde shi batou", 1.2),
            ("Koushin de furu inseki mashouka?", 1.0),
            ("Warukunai", 0.8),
            ("Kyoumi nee sukui hade kandou", 1.2),
            ("Joubi nee kusuri tabe baddo", 1.2),
            ("Chou hidee furu mita me angouka?", 1.0),
            ("Kane ni yoru", 1.5)
        ],
        "Verse 2": [
            ("Joushiki nee kuruippa de rantou", 1.2),
            ("Houchi gee suru inkya de bando", 1.2),
            ("Soushin de kurushii dame cancel wa?", 1.0),
            ("Kusa haeru", 0.8),
            ("Doushite sugu mite shimau no", 1.0),
            ("Doushite sugu itte shimau no", 1.0),
            ("Doushite sugu kowarechau kana", 1.0),
            ("(Pa-para para para)", 0.6)
        ],
        "Pre-Chorus": [
            ("Kinmirai shika katan", 1.1),
            ("Shoppingu mooru no gendai konpyuu", 1.1),
            ("Shoubin tahi ka katan", 1.1),
            ("Monti hooru no keihi de pinbooru", 1.1),
            ("Oirumassaaji hyaku koosu ashiyu tsuki", 1.1),
            ("Yuzu wo soete", 1.1),
            ("Te-te-te-teto teto", 0.8),
            ("Te-te-te-tetoris", 0.8),
            ("Doushite konna saya ni-ni-ni", 1.1)
        ]
    }

    for bagian, baris_list in lirik.items():
        print(f"\n[{bagian}]\n")
        for baris, delay in baris_list:
            print(baris)
            time.sleep(delay)
        time.sleep(1)  # jeda antar bagian

if __name__ == "__main__":
    main()
