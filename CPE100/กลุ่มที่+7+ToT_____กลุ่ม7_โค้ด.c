#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <windows.h>
#include <wchar.h>
#include <locale.h>
#include <sys/stat.h>
#include <time.h>
#include <dirent.h>

#define MAX_LINE_LENGTH 100
#define NUM_CHOICES 4
#define MAX_PATH 260

void AllSong(const char *path)
{
    char searchPath[MAX_PATH];
    sprintf(searchPath, "%s\\*", path);

    WIN32_FIND_DATA findFileData;
    HANDLE hFind = FindFirstFile(searchPath, &findFileData);

    if (hFind == INVALID_HANDLE_VALUE)
    {
        printf("Error opening directory\n");
        return;
    }

    do
    {
        if (strstr(findFileData.cFileName, ".txt") != NULL)
        {
            printf("%s\n", findFileData.cFileName);
        }
    } while (FindNextFile(hFind, &findFileData) != 0);

    FindClose(hFind);
}

void DisplayLyrics(const char *path, const char *songName)
{
    char filePath[MAX_PATH];
    sprintf(filePath, "%s\\%s.txt", path, songName);

    FILE *file = fopen(filePath, "r");

    if (file == NULL)
    {
        printf("Error opening file: %s\n", filePath);
        return;
    }

    setlocale(LC_ALL, "th_TH.UTF-8");

    wchar_t wbuffer[1024];
    while (fgetws(wbuffer, sizeof(wbuffer) / sizeof(wchar_t), file) != NULL)
    {
        wprintf(L"%ls", wbuffer);
    }

    fclose(file);
    wprintf(L"\n");
}

void Addsong()
{
    char file_name[256];
    char data[256];
    int language;
    // change folder directory depend on each file
    const char *directory1 = "C:\\Users\\ASUS\\Desktop\\C\\TeamProject\\International";
    const char *directory2 = "C:\\Users\\ASUS\\Desktop\\C\\TeamProject\\Custom";
    printf("International = 1\nCustom = 2\nChoose the language : ");
    scanf("%d", &language);
    if (language == 1)
    {
        printf("Enter the file name(end with .txt): ");
        scanf("%255s", file_name);
        char full_path[512];
        snprintf(full_path, sizeof(full_path), "%s\\%s", directory1, file_name);
        FILE *file = fopen(full_path, "w");
        if (file == NULL)
        {
            printf("Error\n");
            exit(1);
        }
        while (1)
        {
            printf("Enter the data to write to the file(enter END to end the program): ");
            scanf(" %255[^\n]", data);
            if (strcmp(data, "END") == 0)
            {
                break;
            }
            fprintf(file, "%s\n", data);
        }
        fclose(file);
    }
    if (language == 2)
    {
        printf("Enter the file name(end with .txt): ");
        scanf("%255s", file_name);
        char full_path[512];
        snprintf(full_path, sizeof(full_path), "%s\\%s", directory2, file_name);
        FILE *file = fopen(full_path, "w");
        if (file == NULL)
        {
            printf("Error\n");
            exit(1);
        }
        while (1)
        {
            printf("Enter the data to write to the file(enter END to end the program): ");
            scanf(" %255[^\n]", data);
            if (strcmp(data, "END") == 0)
            {
                break;
            }
            fprintf(file, "%s\n", data);
        }
        fclose(file);
    }
}

void searchlyrics()
{
    char songName[256];
    int language;
    // change folder directory depend on each file
    const char *directory1 = "C:\\Users\\ASUS\\Desktop\\C\\TeamProject\\International";
    const char *directory2 = "C:\\Users\\ASUS\\Desktop\\C\\TeamProject\\Custom";
    printf("\nInternational = 1\nCustom = 2\nChoose the language : ");
    scanf("%d", &language);
    printf("\n");
    if (language == 1)
    {
        AllSong(directory2);
        printf("\nEnter the name of the song you wish to view the lyrics: ");
        scanf("%255s", songName);
        DisplayLyrics(directory1, songName);
    }
    if (language == 2)
    {
        AllSong(directory2);
        printf("\nEnter the name of the song you wish to view the lyrics: ");
        scanf("%255s", songName);
        DisplayLyrics(directory2, songName);
    }
}

void guesser(const char *path)
{
    DIR *d;
    struct dirent *dir;
    char *files[1000];
    int num_files = 0;
    int score = 0;
    int i;
    char guess[256];
    char choice[2];

    srand(time(NULL)); // for random selection

    d = opendir(path);
    if (d)
    {
        while ((dir = readdir(d)) != NULL)
        {
            if (strstr(dir->d_name, ".txt") != NULL)
            {
                if (num_files < 1000)
                {
                    files[num_files] = malloc(strlen(dir->d_name) + 1);
                    strcpy(files[num_files], dir->d_name);
                    num_files++;
                }
            }
        }
        closedir(d);
    }

    do
    {
        int selected_file = rand() % num_files;
        char *songName = malloc(strlen(files[selected_file]) + 1);
        strcpy(songName, files[selected_file]);
        strtok(songName, "."); // remove .txt
        DisplayLyrics(path, songName);

        printf("Guess the song name: ");
        scanf(" %255[^\n]", guess);

        if (strcmp(guess, songName) == 0)
        {
            printf("Congratulations! You guessed correctly. The song is %s.\n", songName);
            score++;
        }
        else
        {
            printf("Sorry, that's incorrect. The song is %s.\n", songName);
        }

        printf("Do you want to continue playing? (Y/N): ");
        scanf(" %1[^\n]", choice);

        free(songName);
    } while (strcmp(choice, "Y") == 0 || strcmp(choice, "y") == 0);

    printf("Your total score is %d. Press C to continue.\n", score);
    scanf(" %1[^\n]", choice);

    for (i = 0; i < num_files; i++)
    {
        free(files[i]);
    }
}

void playguess()
{
    int language;
    // change folder directory depend on each file
    const char *directory1 = "C:\\Users\\ASUS\\Desktop\\C\\TeamProject\\International";
    const char *directory2 = "C:\\Users\\ASUS\\Desktop\\C\\TeamProject\\Custom";
    printf("\nInternational = 1\nCustom = 2\nChoose the language : ");
    scanf("%d", &language);
    printf("\n");
    if (language == 1)
    {
        guesser(directory1);
    }
    if (language == 2)
    {
        guesser(directory2);
    }
}

int main()
{
    int choice;
    int addstop;
    char ending[256];
    while (1)
    {
        printf("Welcome to Lyrics Guesser\nType 1 to start the game\nType 2 to add songs to the folder\nType 3 to view songs and lyrics list\nType 4 to end the program\n");
        scanf("%d", &choice);
        if (choice == 1)
        {
            while (1)
            {
                playguess();
                printf("Do you wish to continue(Y/N) : ");
                scanf(" %255[^\n]", ending);
                if (strcmp(ending, "N") == 0 || strcmp(ending, "n") == 0)
                {
                    break;
                }
            }
        }
        if (choice == 2)
        {
            while (1)
            {
                Addsong();
                printf("Do you wish to continue(Y/N) : ");
                scanf(" %255[^\n]", ending);
                if (strcmp(ending, "N") == 0 || strcmp(ending, "n") == 0)
                {
                    break;
                }
            }
        }
        if (choice == 3)
        {
            while (1)
            {
                searchlyrics();
                printf("Do you wish to continue(Y/N) : ");
                scanf(" %255[^\n]", ending);
                if (strcmp(ending, "N") == 0 || strcmp(ending, "n") == 0)
                {
                    break;
                }
            }
        }
        if (choice == 4)
        {
            break;
        }
    }
    return 0;
}
